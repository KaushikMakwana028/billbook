<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'core/My_Controller.php');

class Category extends My_Controller
{
    public function index($editId = null)
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $category = null;

        if ($editId !== null) {
            $category = $this->getOwnedCategory((int) $editId, $adminId);

            if (empty($category)) {
                $this->session->set_flashdata('error', 'Category not found');
                redirect('category');
                return;
            }
        }

        $data = $this->buildViewData($adminId);
        $data['editCategory'] = $category;
        $data['pageTitle'] = 'Category';

        $this->load->view('header', $data);
        $this->load->view('category_view', $data);
        $this->load->view('footer', $data);
    }

    public function store()
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() === false) {
            $data = $this->buildViewData($adminId);
            $data['pageTitle'] = 'Category';

            $this->load->view('header', $data);
            $this->load->view('category_view', $data);
            $this->load->view('footer', $data);
            return;
        }

        $name = trim((string) $this->input->post('name'));

        $exists = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->where('LOWER(name)', strtolower($name))
            ->get('category')
            ->row();

        if ($exists) {
            $this->session->set_flashdata('error', 'Category name already exists');
            redirect('category');
            return;
        }

        $inserted = $this->db->insert('category', [
            'admin_id' => $adminId,
            'name' => $name,
            'isActive' => 1,
            'created_on' => date('Y-m-d')
        ]);

        if (!$inserted) {
            $dbError = $this->db->error();
            $this->session->set_flashdata('error', !empty($dbError['message']) ? $dbError['message'] : 'Category could not be saved');
            redirect('category');
            return;
        }

        $this->session->set_flashdata('success', 'Category added successfully');
        redirect('category');
    }

    public function edit($id)
    {
        $this->index((int) $id);
    }

    public function update($id)
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $categoryId = (int) $id;
        $category = $this->getOwnedCategory($categoryId, $adminId);

        if (empty($category)) {
            $this->session->set_flashdata('error', 'Category not found');
            redirect('category');
            return;
        }

        $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() === false) {
            $data = $this->buildViewData($adminId);
            $data['editCategory'] = $category;
            $data['pageTitle'] = 'Category';

            $this->load->view('header', $data);
            $this->load->view('category_view', $data);
            $this->load->view('footer', $data);
            return;
        }

        $name = trim((string) $this->input->post('name'));

        $exists = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->where('id !=', $categoryId)
            ->where('LOWER(name)', strtolower($name))
            ->get('category')
            ->row();

        if ($exists) {
            $this->session->set_flashdata('error', 'Category name already exists');
            redirect('category/edit/' . $categoryId);
            return;
        }

        $updated = $this->db
            ->where('id', $categoryId)
            ->where('admin_id', $adminId)
            ->update('category', ['name' => $name]);

        if (!$updated) {
            $dbError = $this->db->error();
            $this->session->set_flashdata('error', !empty($dbError['message']) ? $dbError['message'] : 'Category could not be updated');
            redirect('category/edit/' . $categoryId);
            return;
        }

        $this->session->set_flashdata('success', 'Category updated successfully');
        redirect('category');
    }

    public function delete($id)
    {
        $adminId = (int) ($this->admin['user_id'] ?? 0);
        $categoryId = (int) $id;
        $category = $this->getOwnedCategory($categoryId, $adminId);

        if (empty($category)) {
            $this->session->set_flashdata('error', 'Category not found');
            redirect('category');
            return;
        }

        $deleted = $this->db
            ->where('id', $categoryId)
            ->where('admin_id', $adminId)
            ->update('category', ['isActive' => 0]);

        if (!$deleted) {
            $dbError = $this->db->error();
            $this->session->set_flashdata('error', !empty($dbError['message']) ? $dbError['message'] : 'Category could not be deleted');
            redirect('category');
            return;
        }

        $this->session->set_flashdata('success', 'Category deleted successfully');
        redirect('category');
    }

    private function getOwnedCategory(int $categoryId, int $adminId): ?array
    {
        $row = $this->db
            ->where('id', $categoryId)
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->get('category')
            ->row_array();

        return $row ?: null;
    }

    private function buildViewData(int $adminId): array
    {
        $categories = $this->db
            ->where('admin_id', $adminId)
            ->where('isActive', 1)
            ->order_by('id', 'DESC')
            ->get('category')
            ->result_array();

        return [
            'categories' => $categories,
            'editCategory' => null,
            'adminName' => $this->admin['user_name'] ?? ($this->admin['name'] ?? 'Admin'),
            'userName' => $this->admin['user_name'] ?? ($this->admin['name'] ?? 'Admin'),
            'userEmail' => $this->admin['email'] ?? '',
            'businessName' => $this->admin['business_name'] ?? 'Bills Book',
            'role' => (!empty($this->admin['role']) && (string) $this->admin['role'] !== '1') ? (string) $this->admin['role'] : 'admin',
            'profileImage' => $this->admin['profile_image'] ?? '',
            'profileUrl' => base_url('profile'),
            'adminId' => $adminId
        ];
    }
}
