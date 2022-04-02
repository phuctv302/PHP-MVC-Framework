<?php

namespace controllers;

use core\Application;
use core\Controller;
use models\EditForm;
use models\ImageForm;

class UserController extends Controller {
    public function updateUser($request){
        $edit_form = new EditForm();
        $edit_form->loadData($request->getBody());

        if ($edit_form->validate() && $edit_form->updateUser($request->getBody())){
            Application::$app->session->setFlash('success', 'Update data successfully');
            Application::$app->response->redirect('/profile');
            return;
        } else if (!$edit_form->validate()){
            Application::$app->session->setFlash('error', 'Oops! Invalid input data.');
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => $edit_form,
            'user' => Application::$app->user,
        ]);
    }

    public function updatePhoto($request, $response){
        $image_form = new ImageForm();
        if (isset($_FILES['photo']) && !empty($_FILES['photo'])){
            $image_form->loadData($request->getBody());

            if ($image_form->validate() && $image_form->uploadUserPhoto($request->getBody())){
                Application::$app->session->setFlash('success', "Update photo successfully");
                $response->redirect('/profile');
                return;
            }
            $response->redirect('/profile');
            //            $this->setLayout('main');
            //            return $this->render('profile', [
            //                'model' => $image_form,
            //                'user' => Application::$app->user,
            //            ]);
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => Application::$app->user,
            'user' => Application::$app->user,
        ]);
    }


}
