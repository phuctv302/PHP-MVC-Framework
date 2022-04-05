<?php

namespace controllers;

use core\Application;
use core\Controller;
use forms\EditForm;
use forms\ImageForm;
use models\User;
use services\ImageUploadService;

class UserController extends Controller {
    public function updateUser($request){
        $edit_form = new EditForm();
        $edit_form->loadData($request->getBody());
        $user = new User();

        // validate form
        if (!$edit_form->validate()){
            Application::$app->session->setFlash('error', 'Oops! Invalid input data.');
            $this->setLayout('main');
            return $this->render('profile', [
                'model' => $edit_form,
                'user' => Application::$app->user,
            ]);
        }

        // ON SUCCESS
        $user->updateUser($edit_form);

        Application::$app->session->setFlash('success', 'Update data successfully');
        Application::$app->response->redirect('/profile');
        exit;
    }

    public function updatePhoto($request, $response){
        $image_form = new ImageForm();
        $user = new User();

        // TODO: use image upload service to check
        if (ImageUploadService::checkImageExist('photo')){
            $image_form->loadData($request->getBody());

            if ($image_form->validate() && $user->uploadUserPhoto($request->getBody())){
                Application::$app->session->setFlash('success', "Update photo successfully");
                $response->redirect('/profile');
                return;
            }
            $response->redirect('/profile');
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => Application::$app->user,
            'user' => Application::$app->user,
        ]);
    }


}
