<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	class Category extends CI_Controller {

		public function index(){

			$this->load->model('Category_model');
			$queryString = $this->input->get('q');
			$params['queryString'] = $queryString;
			$categories = $this->Category_model->getCategories($params);
			$data['categories'] = $categories;
			$data['queryString'] = $queryString;
			$this->load->view('admin/category/list', $data);

		}
		public function create(){

			$this->load->helper('common_helper');
			$config['upload_path']          = './public/uploads/category/';
            $config['allowed_types']        = 'gif|jpg|png';
			$config['encrypt_name']        = true;


            $this->load->library('upload', $config);


			$this->load->model('Category_model');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
			$this->form_validation->set_rules('name','Name','trim|required');

			if($this->form_validation->run() == TRUE){

				if(!empty($_FILES['image']['name'])){
					if($this->upload->do_upload('image')){
						$data = $this->upload->data();
						resizeImage($config['upload_path'].$data['file_name'], $config['upload_path'].'thumb/'.$data['file_name'], 300,270);
						$formArray['image'] = $data['file_name'];
						$formArray['name'] = $this->input->post('name');
						$formArray['status'] = $this->input->post('status');
						$formArray['created_at'] = date('Y-m-d H:i:s');
						$this->Category_model->create($formArray);
						$this->session->set_flashdata('success', 'Category added successfully');
						redirect(base_url().'admin/category/index');
					}else{
						$error = $this->upload->display_errors('<p class="invalid-feedback">','</p>');
						$data['errorImageUpload'] = $error;
						$this->load->view('admin/category/create', $data);
					}

				}else{
					$formArray['name'] = $this->input->post('name');
					$formArray['status'] = $this->input->post('status');
					$formArray['created_at'] = date('Y-m-d H:i:s');
					$this->Category_model->create($formArray);
					$this->session->set_flashdata('success', 'Category added successfully');
					redirect(base_url().'admin/category/index');
				}
			}else{
				$this->load->view('admin/category/create');
			}
			
			

		}

		public function edit($id){
			$this->load->model('Category_model');
			$category = $this->Category_model->getCategory($id);
			
			if(empty($category)){
				$this->session->set_flashdata('error', 'Category Not Found');
				redirect(base_url().'admin/category/index');
			}
			$this->load->helper('common_helper');
			$config['upload_path']          = './public/uploads/category/';
            $config['allowed_types']        = 'gif|jpg|png';
			$config['encrypt_name']        = true;


            $this->load->library('upload', $config);


			$this->load->model('Category_model');
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<p class="invalid-feedback">','</p>');
			$this->form_validation->set_rules('name','Name','trim|required');

			if($this->form_validation->run() == TRUE){
				if(!empty($_FILES['image']['name'])){
					if($this->upload->do_upload('image')){
						$data = $this->upload->data();
						resizeImage($config['upload_path'].$data['file_name'], $config['upload_path'].'thumb/'.$data['file_name'], 300,270);
						$formArray['image'] = $data['file_name'];
						$formArray['name'] = $this->input->post('name');
						$formArray['status'] = $this->input->post('status');
						$formArray['updated_at'] = date('Y-m-d H:i:s');
						$this->Category_model->update($id, $formArray);
						$this->session->set_flashdata('success', 'Category Updated Successfully');
						redirect(base_url().'admin/category/index');
					}else{
						$error = $this->upload->display_errors('<p class="invalid-feedback">','</p>');
						$data['errorImageUpload'] = $error;
						$data['category'] = $category;
						$this->load->view('admin/category/edit', $data);
					}

				}else{
					$formArray['name'] = $this->input->post('name');
					$formArray['status'] = $this->input->post('status');
					$formArray['updated_at'] = date('Y-m-d H:i:s');
					$this->Category_model->update($id, $formArray);
					if(file_exists('./public/uploads/category/'.$category['image'])){
						unlink('./public/uploads/category/'.$category['image']);
					}
					if(file_exists('./public/uploads/category/thumb/'.$category['image'])){
						unlink('./public/uploads/category/thumb/'.$category['image']);
					}
					$this->session->set_flashdata('success', 'Category Updated Successfully');
					redirect(base_url().'admin/category/index');
				}

			}else{
				$data['category'] = $category;
				$this->load->view('admin/category/edit', $data);
			}
		}

		public function delete($id){
			
			$this->load->model('Category_model');
			$category = $this->Category_model->getCategory($id);
			
			if(empty($category)){
				$this->session->set_flashdata('error', 'Category Not Found');
				redirect(base_url().'admin/category/index');
			}

					if(file_exists('./public/uploads/category/'.$category['image'])){
						unlink('./public/uploads/category/'.$category['image']);
					}
					if(file_exists('./public/uploads/category/thumb/'.$category['image'])){
						unlink('./public/uploads/category/thumb/'.$category['image']);
					}
					$this->Category_model->getCategory($id);
					$this->session->set_flashdata('success','Category Deleted Successfully');
					redirect(base_url().'admin/category/index/','refresh');

		}
	}
