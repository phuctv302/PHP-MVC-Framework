<?php

namespace controllers;

use core\Application;
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

        // validate form
        if (!$edit_form->validate()){
            $this->getSession()->setFlash('error', 'Oops! Invalid input data.');
            $this->setLayout('main');
            return $this->render('profile', [
                'model' => $edit_form,
                'user' => $this->getUser(),
            ]);
        }

        // ON SUCCESS
        $user->updateUser($edit_form);

        $this->getSession()->setFlash('success', 'Update data successfully');
        $response->redirect('/profile');
        exit;
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
