<?php

class Dashboard_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
       

    }
   

    public function indexAction()
    {

        
        $members = new Dashboard_Model_Login();
        $form = new Dashboard_Form_Login();
        $this->view->form = $form;
        if($this->getRequest()->isPost()){
            if($form->isValid($_POST)){
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Zend_Auth_Adapter_DbTable($members->getAdapter(),'members');
                $authAdapter->setIdentityColumn('userName')
                            ->setCredentialColumn('password');
                $authAdapter->setIdentity($data['userName'])
                            ->setCredential($data['password']);
                $result = $auth->authenticate($authAdapter);
                if($result->isValid()){
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($authAdapter->getResultRowObject(array('memberID', 'userName','role')));
                    $this->view->successMsg = "you are logged in";
                    $this->_redirect('user');

                } else {
                    $this->view->errorMessage = "Invalid username or password. Please try again.";
                }         
            }
        }
      


    }


}

