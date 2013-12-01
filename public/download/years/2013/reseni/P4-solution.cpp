#include <iostream>
#include <string>
#include <queue>
#include <vector>
#include <map>
#include <utility>
#include <stack>
#include <cassert>
#include <cctype>
#include <cstdlib>
using namespace std;

typedef unsigned short cursor_pos_t;
typedef unsigned short trans_code_t;

class EditorTransformer;

map<EditorTransformer*,trans_code_t> transformerIds;

class Editor
{
  string buf;                 // buffer: textovy obsah editoru
  cursor_pos_t cursor;        // pozice kurzoru v ramci textu
  bool shift;                 // drzime Shift?
  bool recordingMacro;        // nahrava se prave ted makro?
  vector<trans_code_t> macro; // nahrane makro
public:  Editor() : buf(), cursor(0), shift(false), recordingMacro(false),
             macro(vector<trans_code_t>(0))
  {}

  // prace s kurzorem
  cursor_pos_t getCursor() const { return cursor; }
  bool isCursorAtBeginning() const { return (cursor == 0); }
  bool isCursorAtEnd() const { return (cursor == buf.length()); }
  void setCursor(cursor_pos_t pos) { assert(pos <= buf.length()); cursor = pos; }
  void moveCursorLeft() { if (!isCursorAtBeginning()) cursor--; }
  void moveCursorRight() { if (!isCursorAtEnd()) cursor++; }
  void moveCursorHome() { cursor = 0; }
  void moveCursorToEnd() { cursor = buf.length(); }

  // shift
  bool getShift() const { return shift; }
  void pressShift() { assert(!shift); shift = true; }
  void releaseShift() { assert(shift); shift = false; }

  // textovy obsah
  const string& getBuf() const { return buf; }
  void setBuf(string buf) { this->buf = buf; }
  char getCharAfterCursor() const { assert(!isCursorAtEnd()); return buf[cursor]; }
  char getCharBeforeCursor() const { assert(!isCursorAtBeginning()); return buf[cursor-1]; }
  void insertChar(char c) { buf.insert(cursor, 1, c); cursor++; }
  void deleteChar() { assert(!isCursorAtEnd()); buf.erase(cursor, 1); }
  void backspaceChar() { moveCursorLeft(); deleteChar(); }

  // makro
  bool isRecordingMacro() const { return recordingMacro; }
  const vector<trans_code_t>& getMacro() const { return macro; }
  void pushMacroInstruction(EditorTransformer* instr) {
    assert(instr != NULL);
    macro.push_back(transformerIds.at(instr));
  }
  void popMacroInstruction() { macro.pop_back(); }
  void clearMacro() { macro.clear(); }
  void switchRecordingMacro() { recordingMacro = !recordingMacro; }
  void setMacro(vector<trans_code_t> m) { macro = m; }
  void runMacro();

  // relacni operatory, aby se dal Editor pouzit ve vyhledavaci mape
  bool operator < (const Editor& other) const {
    if (cursor != other.cursor) {
      return (cursor < other.cursor);
    }
    if (shift != other.shift) {
      return (shift < other.shift);
    }
    if (recordingMacro != other.recordingMacro) {
      return recordingMacro < other.recordingMacro;
    }
    if (macro != other.macro) {
      return macro < other.macro;
    }
    return (buf < other.buf);
  }

  bool operator == (const Editor& other) const {
    return (cursor == other.cursor
      && shift == other.shift
      && recordingMacro == other.recordingMacro
      && buf == other.buf
      && macro == other.macro
    );
  }

  bool operator != (const Editor& other) const { return !(*this == other); }
};


ostream& operator<<(ostream& out, const Editor& ed) {
  out << "buf: '" << ed.getBuf() << "' cursor: " << ed.getCursor()
      << " shift: " << boolalpha << ed.getShift()
      << " recording: " << ed.isRecordingMacro()
      << " macro def:";
  for (vector<trans_code_t>::const_iterator i = ed.getMacro().begin();
       i != ed.getMacro().end(); ++i)
  {
    out << " " << *i;
  }
  out << "|";
  return out;
}


class EditorTransformer {
  const char code;
protected:
  EditorTransformer(char code) : code(code) {}
public:
  virtual ~EditorTransformer() {}
  virtual char getCode() const { return code; }
  virtual void perform(Editor& editor) = 0;
  virtual void undo(Editor& editor) = 0;
};

class CharacterInserter : public EditorTransformer {
  const char charToInsert;
public:
  CharacterInserter(char charToInsert)
    : EditorTransformer(toupper(charToInsert)), charToInsert(charToInsert) {}
  void perform(Editor& editor) {
    editor.insertChar(charToInsert);
    if (editor.isRecordingMacro()) editor.pushMacroInstruction(this);
  }
  void undo(Editor& editor) {
    editor.backspaceChar();
    if (editor.isRecordingMacro()) editor.popMacroInstruction();
  }
};

class Backspacer : public EditorTransformer {
  char deletedChar;
public:
  Backspacer() : EditorTransformer('9'), deletedChar() {}
  void perform(Editor& editor) {
    if (editor.isCursorAtBeginning()) {
      deletedChar = 0;
    }
    else {
      deletedChar = editor.getCharBeforeCursor();
      editor.backspaceChar();
    }
    if (editor.isRecordingMacro()) editor.pushMacroInstruction(this);
  }
  void undo(Editor& editor) {
    if (deletedChar) editor.insertChar(deletedChar);
    if (editor.isRecordingMacro()) editor.popMacroInstruction();
  }
};

class Deleter : public EditorTransformer {
  char deletedChar;
public:
  Deleter() : EditorTransformer('3'), deletedChar() {}
  void perform(Editor& editor) {
    if (editor.isCursorAtEnd()) {
      deletedChar = 0;
    }
    else {
      deletedChar = editor.getCharAfterCursor();
      editor.deleteChar();
    }
    if (editor.isRecordingMacro()) editor.pushMacroInstruction(this);
  }
  void undo(Editor& editor) {
    if (deletedChar) {
      editor.insertChar(deletedChar);
      editor.moveCursorLeft();
    }
    if (editor.isRecordingMacro()) editor.popMacroInstruction();
  }
};

class ShiftPresser : public EditorTransformer {
public:
  ShiftPresser() : EditorTransformer('8') {}
  void perform(Editor& editor) { editor.pressShift(); }
  void undo(Editor& editor) { editor.releaseShift(); }
};

class CursorMover : public EditorTransformer {
  void (Editor::* const moverFn)();
  cursor_pos_t origCurPos;
public:
  CursorMover(void (Editor::* moverFn)(), char code)
    : EditorTransformer(code), moverFn(moverFn), origCurPos() {}
  void perform(Editor& editor) {
    origCurPos = editor.getCursor();
    (editor.*moverFn)();
    if (editor.isRecordingMacro()) editor.pushMacroInstruction(this);
  }
  void undo(Editor& editor) {
    editor.setCursor(origCurPos);
    if (editor.isRecordingMacro()) editor.popMacroInstruction();
  }
};

class MacroRecordingStarter : public EditorTransformer {
  vector<trans_code_t> origMacro;
public:
  MacroRecordingStarter() : EditorTransformer('2'), origMacro(NULL) {}
  void perform(Editor& editor) {
    assert(!editor.isRecordingMacro());
    origMacro = editor.getMacro();
    editor.clearMacro();
    editor.switchRecordingMacro();
  }
  void undo(Editor& editor) {
    assert(editor.isRecordingMacro());
    editor.switchRecordingMacro();
    editor.setMacro(origMacro);
  }
};

class MacroRecordingStopper : public EditorTransformer {
public:
  MacroRecordingStopper() : EditorTransformer('2') {}
  void perform(Editor& editor) {
    assert(editor.isRecordingMacro());
    editor.switchRecordingMacro();
  }
  void undo(Editor& editor) {
    assert(!editor.isRecordingMacro());
    editor.switchRecordingMacro();
  }
};

class MacroRunner : public EditorTransformer {
  Editor origState;
public:
  MacroRunner() : EditorTransformer('5'), origState() {}
  void perform(Editor& editor) {
    origState = editor;
    editor.runMacro();
  }
  void undo(Editor& editor) { editor = origState; }
};


CharacterInserter insLowerL('l');
CharacterInserter insLowerO('o');
CharacterInserter insUpperS('S');
ShiftPresser shiftPresser = ShiftPresser();
Backspacer backspacer;
Deleter deleter;
CursorMover leftMover(&Editor::moveCursorLeft, '4');
CursorMover rightMover(&Editor::moveCursorRight, '6');
CursorMover homeMover(&Editor::moveCursorHome, '7');
CursorMover endMover(&Editor::moveCursorToEnd, '1');
MacroRecordingStarter macroRecStarter;
MacroRecordingStopper macroRecStopper;
MacroRunner macroRunner;


EditorTransformer* transformers[] = {
  NULL,
  &insLowerL, &insLowerO, &insUpperS,
  &shiftPresser, &backspacer, &deleter,
  &leftMover, &rightMover, &homeMover, &endMover,
  &macroRecStarter, &macroRecStopper, &macroRunner,
};

void Editor::runMacro() {
  vector<trans_code_t> instrs(macro);
  for (vector<trans_code_t>::iterator i = instrs.begin(); i != instrs.end(); ++i) {
    transformers[*i]->perform(*this);
  }
}


string srcBuf; // vychozi text
string dstBuf; // cilovy text

// nachystame mapu, ktera obsahuje iteratory odkazujici na prvky teto mapy
// inspirovano: http://stackoverflow.com/questions/1403501/stl-map-onto-itself
template<typename Key>
struct discovered_map;

template<typename Key>
struct discovered_map_iterator : discovered_map<Key>::iterator {
  discovered_map_iterator(typename discovered_map<Key>::iterator i) : discovered_map<Key>::iterator(i) {}
};

template<typename Key>
struct discovered_map : map<Key, pair<discovered_map_iterator<Key>,char> > {};

discovered_map<Editor> discovered; // mapa stavu editoru na predchozi stavy editoru;
                                   // pod discovered[st] je odkaz na stav, ktery stavu st predchazel


/**
 * Ze stavu state provede zadanou transformaci a zaradi takto ziskany stav do
 * fronty k dalsimu zpracovani.
 *
 * @param state       stav, ktery prozkoumat
 * @param stateIter   odkaz do mapy stavu na tento stav
 * @param qNext       fronta, do ktere dat novy naslednicky stav
 * @param transformer transformer, ktery aplikovat
 */
void discover(
      Editor& state, const discovered_map<Editor>::iterator& stateIter,
      queue<discovered_map<Editor>::iterator>& qNext,
      EditorTransformer& transformer);


int main(int argc, char* argv[])
{
  if (argc != 3) {
    cerr << "Usage: " << argv[0] << " SRCTEXT DESTTEXT" << endl;
    return 1;
  }

  // naplnit si vyhledavaci mapu transformeru
  for (size_t i = 1; i < sizeof(transformers)/sizeof(transformers[0]); i++) {
    transformerIds[transformers[i]] = i;
  }

  srcBuf = string(argv[1]);
  dstBuf = string(argv[2]);

  Editor editor;
  editor.setBuf(srcBuf);

  // postupne prohledavat vsechny moznosti po 1, 2, ... stisknutych klavesach;

  // dosud nalezene stavy editoru jsou ulozeny v mape discovered, do fronty
  //   budeme zarazovat "ukazatele" (iteratory) do teto mapy
  queue<discovered_map<Editor>::iterator> q;

  // v mape discovered si ke kazdemu editoru pamatujeme jeho predchudce a kod operace, ktera jej upravila
  pair<discovered_map<Editor>::iterator, bool> insertResult =
    discovered.insert(make_pair(editor, make_pair(discovered.end(), 0)));

  q.push(insertResult.first);

  for ( ; ; ) {
    assert(!q.empty());
    const discovered_map<Editor>::iterator& stateIter = q.front();
    Editor state = stateIter->first;

    // do fronty pridame vsechny nasledniky aktualniho stavu
    // ...ale jen ty, kteri davaji smysl
    // prozkoumavane stavy radime abecedne dle stisknute klavesy
    bool wasShift = state.getShift();

    if (wasShift) state.releaseShift();

    discover(state, stateIter, q, insLowerL);
    discover(state, stateIter, q, insLowerO);

    if (wasShift) {
      state.pressShift();
      discover(state, stateIter, q, insUpperS);
      state.releaseShift();
    }

    if (!state.isCursorAtEnd()) discover(state, stateIter, q, endMover);

    if (state.isRecordingMacro()) discover(state, stateIter, q, macroRecStopper);
    else discover(state, stateIter, q, macroRecStarter);

    if (!state.isCursorAtEnd()) discover(state, stateIter, q, deleter);
    if (!state.isCursorAtBeginning()) discover(state, stateIter, q, leftMover);
    if (!state.getMacro().empty()) discover(state, stateIter, q, macroRunner);
    if (!state.isCursorAtEnd()) discover(state, stateIter, q, rightMover);
    if (!state.isCursorAtBeginning()) discover(state, stateIter, q, homeMover);
    if (!wasShift) discover(state, stateIter, q, shiftPresser);
    if (!state.isCursorAtBeginning()) discover(state, stateIter, q, backspacer);

    q.pop();
  }
}

void discover(
      Editor& state, const discovered_map<Editor>::iterator& stateIter,
      queue<discovered_map<Editor>::iterator>& q,
      EditorTransformer& transformer)
{
  transformer.perform(state); // zkusit provest pozadovanou transformaci

  // zkusit, jestli jsme tento naslednicky stav jiz videli
  // pokud ne, vlozi se na nej odkaz, i s kodem transformace
  pair<discovered_map<Editor>::iterator,bool> ins =
    discovered.insert(make_pair(state, make_pair(stateIter, transformer.getCode())));
  if (ins.second) {
    // dosud neznamy stav
    if (state.getBuf() == dstBuf) {
      cerr << "Nalezeno!" << endl;
        cerr << "Editor:    " << state << endl;
        // kody dostaneme pozpatky - pres zasobnik je otocime do spravneho poradi
        stack<char> result;
        pair<discovered_map<Editor>::iterator,char> prevPair = discovered.at(state);
        char prevOp = prevPair.second;
        discovered_map<Editor>::iterator prevIter = prevPair.first;
        while (prevIter != discovered.end()) {
          cerr << "predchozi: " << prevIter->first << endl;
          result.push(prevOp);
          prevOp = prevIter->second.second;
          prevIter = prevIter->second.first;
        }
        while (!result.empty()) {
          cout << result.top();
          result.pop();
        }
        cout << endl;
        exit(0);
    }

    q.push(ins.first); // zaradit pro dalsi zpracovani
  }

  transformer.undo(state); // vratit do puvodniho stavu pro dalsi transformace
}
