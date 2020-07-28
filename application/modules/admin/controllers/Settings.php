<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_builder');
        $this->load->model('AdminModel');
        $this->load->library('csvimport');
    }

    public function index()
    {
    }
    public function skills()
    {
        $crud = $this->generate_crud('skills');
        $crud->columns('name');


        // disable direct create / delete Frontend User
        $crud->unset_add();
        $crud->unset_delete();

        $this->mPageTitle = 'Manage Skills';
        $this->render_crud();
    }
    public function skills_add()
    {
        $form = $this->form_builder->create_form();
        if ($form->validate()) {
            $name = $this->input->POST('name');
            $this->AdminModel->saveSkill($name);
            $this->system_message->set_success("Skills saved successfully.");
        }
        $this->mPageTitle = 'Add Skills';
        $this->mViewData['form'] = $form;

        $this->render('settings/skills_add');
    }
    public function importcsv()
    {
        $data['error'] = '';    //initialize image upload error array to empty

        $config['upload_path'] = 'uploads/collage_csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '20000';

        $this->load->library('upload', $config);
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            print_r('Error: '.$this->upload->display_errors());
            // $data['error'] = $this->upload->display_errors();
        // $this->load->view('csvindex', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  'uploads/collage_csv/'.$file_data['file_name'];

            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                //print_r($csv_array);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                  'Institution_ID' => $row['Institution_ID'],
                  'Institution_Name' => $row['Institution_Name'],
                  'Institution_Address' => $row['Institution_Address'],
                  'Institution_City' => $row['Institution_City'],
                  'Institution_State' => $row['Institution_State'],
                  'Institution_Zip' => $row['Institution_Zip'],
                  'Institution_Phone' => $row['Institution_Phone'],
                  'Institution_OPEID' => $row['Institution_OPEID'],
                  'Institution_IPEDS_UnitID' => $row['Institution_IPEDS_UnitID'],
                  'Institution_Web_Address' => $row['Institution_Web_Address'],
                  'Campus_Name' => $row['Campus_Name'],
                  'Campus_Address' => $row['Campus_Address'],
                  'Campus_City' => $row['Campus_City'],
                  'Campus_State' => $row['Campus_State'],
                  'Campus_Zip' => $row['Campus_Zip'],
              );

                    $this->AdminModel->insertCollage($insert_data);
                    //echo "<pre>"; print_r($insert_data);
              //$this->csv_model->insert_csv($insert_data);
                }

                //$this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect('admin/settings/importcollages');
            } else {
                $data['error'] = "Error occured";
            }
            //$this->load->view('csvindex', $data);
        }
    }

    public function importcollages()
    {
        $this->mPageTitle = 'Import Collage CSV';
        //$this->mViewData['form'] = $form;
        $this->render('settings/importcollagecsv');
    }

    public function importcompanies(){
      $this->mPageTitle = 'Import Companies CSV';
      $this->render('settings/importcompaniescsv');
    }

    public function importcompaniescsv(){

      $this->load->library('ion_auth');

      $data['error'] = '';    //initialize image upload error array to empty

      $config['upload_path'] = 'uploads/collage_csv/';
      $config['allowed_types'] = 'csv';
      $config['max_size'] = '20000';

      $this->load->library('upload', $config);
      // If upload failed, display error
      if (!$this->upload->do_upload()) {
	redirect('admin');
      } else {
          $file_data = $this->upload->data();
          $file_path =  'uploads/collage_csv/'.$file_data['file_name'];

          if ($this->csvimport->get_array($file_path)) {
              $csv_array = $this->csvimport->get_array($file_path);
              //print_r($csv_array);
              foreach ($csv_array as $row) {
                  $insert_data = array(
                'company' => $row['Employer Name'],
                'user_type' => '3',
                'active' => '2',
                'city' => $row['Employer City'],
                'zip' => $row['Employer ZIP Code'],
                'state' => $row['Employer State Code'],
            );
                  $this->AdminModel->insertCompany($insert_data);
              }

          } else {
              $data['error'] = "Error occured";
          }
          //$this->load->view('csvindex', $data);
      }
	redirect('admin');
    }

    public function termsprivacy()
    {
        $form = $this->form_builder->create_form();
        if ($form->validate()) {
            $terms = $this->input->POST('croot_value');
            $privacy = $this->input->POST('user_vu_size_limit');

            $data = array('terms' => $terms, 'privacy' => $privacy);

            $status = $this->AdminModel->saveSettings($data);
            $this->system_message->set_success("Settings saved successfully.");
        }

        $this->mViewData['settings'] = $this->AdminModel->getSettings();
        $this->mPageTitle = 'Terms and Privacy';
        $this->mViewData['form'] = $form;

        $this->render('settings/termsprivacy');
    }
}
