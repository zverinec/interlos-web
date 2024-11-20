<?php

use Nette\Security\User;
use Nette\Utils\Strings;
use Tracy\Debugger;

class AnswerFormComponent extends BaseComponent
{

    /** @var User */
    private $user;

    public function __construct(User $user) {
        parent::__construct();
        $this->user = $user;
    }

    public function formSubmitted(Nette\Forms\Form $form) {
        if (\Interlos::getLoggedTeam() == null) {
            $form->addError('Odpovídat mohou jen přihlášené týmy.');
            return;
        }
        if(!\Interlos::isGameActive()) {
            $form->addError('Odpovídat lze pouze během hry.');
            return;
        }
        $values = $form->getValues();

        try {
            $task = Interlos::tasks()->find($values["task"]);
            $solution = Strings::toAscii(Strings::upper(strtr($values["solution"], array(" " => ""))));
            Interlos::answers()->insert(Interlos::getLoggedTeam()->id_team, $values["task"], $solution);
            if (Strings::upper($task->code) == Strings::upper($solution)) {
                $this->getPresenter()->flashMessage("Vaše odpověď je správně.", "success");
            }
            else {
                $this->getPresenter()->flashMessage("Vaše odpověď je špatně.", "error");
            }
        }
        catch(Nette\InvalidStateException $e) {
            if ($e->getCode() == AnswersModel::ERROR_TIME_LIMIT) {
                $this->getPresenter()->flashMessage("Od vaší poslední špatné odpovědi ještě neuplynulo 30 sekund.", "error");
                return;
            }
            else {
                $this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
                Debugger::log($e, Debugger::EXCEPTION);
                return;
            }
        }
        catch(\Dibi\DriverException $e) {
            if ($e->getCode() == 1062) {
                $this->getPresenter()->flashMessage("Na zadaný úkol jste již takto jednou odpovídali.", "error");
            }
            else {
                $this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
                Debugger::log($e, Debugger::EXCEPTION);
            }
            return;
        }
        catch(Exception $e) {
            $this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
            Debugger::log($e, Debugger::EXCEPTION);
            return;
        }
        $this->getPresenter()->redirect("this");
    }

    protected function createComponentForm($name) {
        $form = new BaseForm($this, $name);

        // Tasks
        $tasks = Interlos::tasks()
            ->findAllAvailable(Interlos::getLoggedTeam()->id_team)
            ->fetchPairs("id_task", "whole_name");
        $form->addSelect("task", "Úkol", $tasks )
                ->setPrompt("--- Vyberte ---")
                ->addRule(Nette\Forms\Form::FILLED, "Vyberte prosím řešený úkol.");
        // Solution
        $form->addText("solution", "Kód")
                ->addRule(Nette\Forms\Form::FILLED, "Vyplňte prosím řešení úkolu.")
                ->setOption("description","Výsledný kód zadávejte velkými písmeny, bez mezer a bez diakritiky.");;

        $form->addSubmit("solution_submit", "Odeslat řešení");
        $form->onSuccess[] = array($this, "formSubmitted");

        return $form;
    }

    protected function startUp() {
        parent::startUp();
        if (!$this->user->isLoggedIn()) {
            throw new Nette\InvalidStateException("There is no logged team.");
        }
        if (Interlos::isGameEnd()) {
            $this->flashMessage("Čas vypršel.", "error");
            $this->getTemplate()->valid = FALSE;
        }
        else if (!Interlos::isGameStarted()) {
            $this->flashMessage("Hra ještě nezačala.", "error");
            $this->getTemplate()->valid = FALSE;
        }
        else {
            $this->getTemplate()->valid = TRUE;
        }
    }

}
