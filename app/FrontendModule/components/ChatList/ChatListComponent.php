<?php
class ChatListComponent extends BaseListComponent {

	// PUBLIC METHODS

	public function chatSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();
		// Insert a chat post
		try {
			Interlos::chat()->insert(
					Interlos::getUser()->getIdentity()->id_team,
					$values["content"]
			);
			$this->getPresenter()->flashMessage("Příspěvek byl vložen.", "success");
			$this->getPresenter()->redirect("this");
		}
		catch (DibiException $e) {
			$this->flashMessage("Chyba při práci s databází.", "error");
			error_log($e->getTraceAsString());
		}
	}

	// PROTECTED METHODS

	protected function beforeRender() {
		// Paginator
		$paginator = $this->getPaginator();
		$this->getSource()->applyLimit($paginator->itemsPerPage, $paginator->offset);
		// Load template
		$this->getTemplate()->posts = $this->getSource()->fetchAll();
	}

	protected function createComponentChatForm($name) {
		$form = new BaseForm($this, $name);

		$form->addTextArea("content","")
				->addRule(Nette\Forms\Form::FILLED, "Obsah příspěvku není vyplněn.");

		$form->addSubmit("chatSubmit","Přidat příspěvek");
		$form->onSubmit[] = array($this, "chatSubmitted");

		return $form;
	}

}
