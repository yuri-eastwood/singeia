<?php

class ProfessorController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

        public function filters()
        {
            return array(
                'rights'
            );
        }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Professor;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Professor']))
		{
			$model->attributes=$_POST['Professor'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->cpf));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateGeral() 
	{
		$model  = new Professor;
		$pessoa = new Pessoa;
		$endereco = new Endereco;
		$telefone = new Telefone;
                
                $this->performAjaxValidation(array($model,$pessoa,$endereco,$telefone));
                
		if(isset($_POST['Professor']) && isset($_POST['Pessoa']) && isset($_POST['Endereco']) && isset($_POST['Telefone']))
		{
			$pessoa->attributes = $_POST['Pessoa'];
			$model->attributes  = $_POST['Professor'];
			$endereco->attributes = $_POST['Endereco'];
			$telefone->attributes   = $_POST['Telefone'];
			
			$model->cpf = $pessoa->cpf;
			$endereco->cpf = $pessoa->cpf;
			$telefone->cpf = $pessoa->cpf;
			
			if($pessoa->save())
			{
				if($model->save() && $endereco->save() && $telefone->save())
					$this->redirect(array('view','id'=>$model->cpf));
			}
		}

		$this->render('createGeral',array(
			'model'=>$model,
			'pessoa'=>$pessoa,
			'endereco'=>$endereco,
			'telefone'=>$telefone,
		));
	}

	public function actionUpdateGeral($id)
	{
			$model = $this->loadModel($id);
            $pessoa = Pessoa::model()->findByPk($id);
			$endereco = Endereco::model()->findByPk($id);
			$telefone = Telefone::model()->findByPk($id);
                
			$this->performAjaxValidation(array($model,$pessoa,$endereco,$telefone));
                
            if(isset($_POST['Professor']) && isset($_POST['Pessoa']) 
				&& isset($_POST['Endereco']) && isset($_POST['Telefone']))
			{
				$pessoa->attributes = $_POST['Pessoa'];
				$model->attributes  = $_POST['Professor'];
				$endereco->attributes  = $_POST['Endereco'];
				$telefone->attributes  = $_POST['Telefone'];
				
				$model->cpf = $pessoa->cpf;			
				$endereco->cpf = $pessoa->cpf;
				$telefone->cpf = $pessoa->cpf;			
			
				if($pessoa->save())
				{
					if($model->save() && $endereco->save() && $telefone->save())
						$this->redirect(array('view','id'=>$model->cpf));
				}
		}
                
			$this->render('updateGeral',array(
				'model'=>$model,
				'pessoa'=>$pessoa,
				'endereco'=>$endereco,
				'telefone'=>$telefone,
			));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Professor']))
		{
			$model->attributes=$_POST['Professor'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->cpf));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Professor');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Professor('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Professor']))
			$model->attributes=$_GET['Professor'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Professor::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='professor-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
