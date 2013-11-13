<?php
class ChatListComponent extends BaseListComponent {

	// PUBLIC METHODS

	public function chatSubmitted(Nette\Forms\Form $form) {
		$values = $form->getValues();
		if(!empty($values['id_parent']) && $values['id_parent'] !== "null" && is_int(intval($values['id_parent']))) {
			$row = Interlos::chat()->find($values['id_parent']);
			if($row === FALSE) {
				$this->getPresenter()->flashMessage("Zpráva, na kterou chcete reagovat, neexistuje.", "error");
				return;
			}
			if(isSet($row['id_parent'])) {
				$this->getPresenter()->flashMessage("Lze reagovat pouze na zprávy první úrovně.", "error");
				return;
			}

		}
		// Insert a chat post
		try {
			$id = Interlos::chat()->insert(
					Interlos::getUser()->getIdentity()->id_team,
					$values["content"],
					$values['id_parent']
			);
			$this->getPresenter()->flashMessage("Příspěvek byl vložen.", "success");
			$this->getPresenter()->redirect("Default:chat#post-". $values['id_parent']);
		}
		catch (DibiException $e) {
			$this->getPresenter()->flashMessage("Chyba při práci s databází.", "error");
			error_log($e->getTraceAsString());
		}
	}

	// PROTECTED METHODS

	protected function beforeRender() {
		// Paginator
		$paginator = $this->getPaginator();
		$selection = Interlos::chat()->findAllRoot()->applyLimit($paginator->itemsPerPage, $paginator->offset)->fetchPairs(null, 'id_chat');
		if(count($selection) == 0) {
			$selection = array(-1);
		}
		$this->getSource()->where('post_id_chat IN %l OR reply_id_chat IN %l', $selection, $selection)->orderBy('last_post_inserted','DESC')->orderBy('reply_inserted', 'ASC');
		// Load template
		$this->getTemplate()->posts = $this->getSource()->fetchAssoc('post_id_chat,#');
	}

	/** Override paginator to count only root posts */
	protected function createComponentPaginator($name) {
		$paginator = new VisualPaginatorComponent($this, $name);
		$paginator->paginator->itemsPerPage = $this->getLimit();
		$paginator->paginator->itemCount = Interlos::chat()->findAllRoot()->count();
		return $paginator;
	}

	protected function createComponentChatForm($name) {
		$self = $this;
		return new \Nette\Application\UI\Multiplier(function ($parentPost) use($self) {
			$form = new BaseForm();
			$renderer = $form->getRenderer();
			$renderer->wrappers['pair']['container'] = 'div';
			$renderer->wrappers['control']['container'] = null;
			$renderer->wrappers['controls']['container'] = null;

			$form->addHidden('id_parent', $parentPost);

			$form->addTextArea("content",null, 80, 6)
				->addRule(Nette\Forms\Form::FILLED, "Obsah příspěvku není vyplněn.")
				->setAttribute('placeholder', 'Zde zadejte text vašeho příspěvku.');

			$form->addSubmit("chatSubmit","Přidat příspěvek");
			$form->onSuccess[] = array($self, "chatSubmitted");

			return $form;
		});
	}

}
