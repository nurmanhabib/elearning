<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ads extends Admin {

    public function index()
    {
        $ads = Model\Portal\Ads::all();

        $this->template->build('index', compact('ads'));
    }

    public function create()
    {
        $this->form_validation->set_rules('code', 'Code Ads', 'trim|required|is_unique[ads.code]');
        $this->form_validation->set_rules('name', 'Name Ads', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->template->build('create');
        } else {
            $ads = new Model\Portal\Ads;
            $ads->fill($this->input->post());
            $ads->save();

            set_message_success('Ads berhasil ditambahkan.');

            redirect('ads', 'refresh');
        }
    }

    public function edit($id)
    {
        $ads = Model\Portal\Ads::where('code', $id)->first();

        if ($ads) {
            $this->form_validation->set_rules('show', 'Tampil', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->template->build('edit', compact('ads'));
            } else {
                if (isset($_FILES['image'])) {
                    $manager    = new Intervention\Image\ImageManager;
                    $image      = $manager->make($_FILES['image']['tmp_name']);

                    $image->resize($ads->width, $ads->height);
                    $image->save(PATH_ADS . '/ads_'.$ads->code.'.jpg');

                    $ads->image = 'ads_'.$ads->code.'.jpg';
                }

                $ads->show  = $this->input->post('show');
                $ads->link  = $this->input->post('link');
                $ads->save();

                set_message_success('Iklan berhasil diperbarui.');

                redirect('ads', 'refresh');
            }
        } else {
            set_message_error('Iklan tidak ada');

            redirect('ads', 'refresh');
        }
    }

}

/* End of file Ads.php */
/* Location: ./application/modules/ads/controllers/Ads.php */