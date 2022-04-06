<?php

namespace controllers;

use core\Controller;
use forms\EditForm;
use forms\ImageForm;
use models\User;
use services\ImageUploadService;

class UserController extends Controller {
    public function updateUser($request, $response){
        $edit_form = new EditForm();
        $edit_form->loadData($request->getBody());
        $user = new User();

        // ON SUCCESS
        if ($edit_form->validate() && $user->updateUser($edit_form)){
            $user->updateUser($edit_form);

            $this->getSession()->setFlash('success', 'Update data successfully');
            $response->redirect('/profile');
        } else if (!$edit_form->validate()){
            // validate form error
            $this->getSession()->setFlash('error', 'Oops! Invalid input data.');
        } else if (!$user->updateUser($edit_form)){
            // error updating user
            $this->getSession()->setFlash('error', 'Error updating data.');
        }

        // ERROR
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => $edit_form,
            'user' => $this->getUser(),
        ]);
    }

    public function updatePhoto($request, $response){
        $image_form = new ImageForm();
        $user = new User();

        if (ImageUploadService::checkImageExist('photo')){
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
