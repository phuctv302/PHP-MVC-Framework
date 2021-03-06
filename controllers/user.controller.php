<?php

namespace controllers;

use core\Controller;
use forms\EditForm;
use forms\ImageForm;
use models\User;
use services\ImageUploadService;

/**
 * operations related to user: update info, upload photo,...
 * */
class UserController extends Controller {

    /**
     * Validate and update user's information
     * fields can not be updated: email and username
     * @param \core\Request $request
     * @param \core\Response $response
     * */
    public function updateUser($request, $response){
        $edit_form = new EditForm();
        $edit_form->loadData($request->getBody());
        $user = new User();

        // ERROR
        if (!$edit_form->validate()){
            // validate form error
            $this->getSession()->setFlash('error', 'Oops! Invalid input data.');

            $this->setLayout('main');
            return $this->render('profile', [
                'model' => $edit_form,
                'user' => $this->getUser(),
            ]);
        }

        if (!$user->updateUser($edit_form)){
            // validate form error
            $this->getSession()->setFlash('error', 'Oops! Invalid input data.');

            $this->setLayout('main');
            return $this->render('profile', [
                'model' => $edit_form,
                'user' => $this->getUser(),
            ]);
        }

        // ON SUCCESS
        $this->getSession()->setFlash('success', 'Update data successfully');
        $response->redirect('/profile');
    }

    /**
     * Only for updating user's photo
     * @param \core\Request $request
     * @param \core\Response $response
     * */
    public function updatePhoto($request, $response){
        $image_form = new ImageForm();
        $user = new User();

        if (ImageUploadService::checkFileExist('photo')){
            $image_form->loadData($request->getBody());

            if ($image_form->validate() && $user->uploadUserPhoto($request->getBody())){
                $this->getSession()->setFlash('success', "Update photo successfully");
                $response->redirect('/profile');
            }
            $response->redirect('/profile');
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => $this->getUser(),
            'user' => $this->getUser(),
        ]);
    }
}
