<?php
class AnswerFormComponent extends BaseComponent
{

	public function formSubmitted(Form $form) {
		$values = $form->getValues();

		try {
			$task = Interlos::tasks()->find($values["task"]);
			$solution = strtoupper(strtr($values["solution"], array(" " => "")));
			Interlos::answers()->insert(Interlos::getLoggedTeam()->id_team, $values["task"], $solution);
			if ($task->code == $solution) {
				$this->getPresenter()->flashMessage("Vaše odpověď je správně.", "success");
			}
			else {
				$this->getPresenter()->flashMessage("Vaše odpověď je špatně.", "error");
			}
		}
		catch(InvalidStateException $e) {
			if ($e->getCode() == AnswersModel::ERROR_TIME_LIMIT) {
				$this->getPresenter()->flashMessage("Od vaší poslední špatné odpovědi ještě neuplynulo 30 sekund.", "error");
				return;
			}
			else {
				$this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
				Debug::processException($e, TRUE);
				//error_log($e->getTraceAsString());
				return;
			}
		}
		catch(DibiDriverException $e) {
			if ($e->getCode() == 1062) {
				$this->getPresenter()->flashMessage("Na zadaný úkol jste již takto jednou odpovídali.", "error");
			}
			else {
				$this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
				Debug::processException($e, TRUE);
				//error_log($e->getTraceAsString());
			}
			return;
		}
		catch(Exception $e) {
			$this->getPresenter()->flashMessage("Stala se neočekávaná chyba.", "error");
			Debug::processException($e, TRUE);
			//error_log($e->getTraceAsString());
			return;
		}
		$this->getPresenter()->redirect("this");
	}

	protected function createComponentForm($name) {
		$form = new BaseForm($this, $name);

		// Tasks
		$tasks = array(NULL => " ---- Vybrat ---- ") + Interlos::tasks()
			->findAllAvaiable(Interlos::getLoggedTeam()->id_team)
			->fetchPairs("id_task", "code_name");
		$form->addSelect("task", "Úkol", $tasks )
				->skipFirst()
				->addRule(Form::FILLED, "Vyberte prosím řešený úkol.");
		// Solution
		$form->addText("solution", "Kód")
				->addRule(Form::FILLED, "Vyplňte prosím řešení úkolu.")
				->setOption("description","Výsledný kód zadávejte velkými písmeny, bez mezer a bez diakritiky.");;

		$form->addSubmit("solution_submit", "Odeslat řešení");
		$form->onSubmit[] = array($this, "formSubmitted");

		return $form;
	}

	protected function startUp() {
		parent::startUp();
		if (!Environment::getUser()->isLoggedIn()) {
			throw new InvalidStateException("There is no logged team.");
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
