<?php

class ControllerModulemymodul extends Controller
{

    public function index()
    {
        $this->load->language('module/mymodul'); //подключаем любой языковой файл
        $data['heading_title'] = $this->language->get('heading_title'); //объявляем переменную heading_title с данными из языкового файла

        $this->document->addStyle('catalog/view/theme/default/stylesheet/myModul.css');

        // получаем id продукта
        if (isset($this->request->get['product_id'])) {
            $product_id = (int)$this->request->get['product_id'];
        } else {
            $product_id = 0;
        }
        $data['content'] = [];

        if ($product_id != 0) {
            // подключаем необходимые модули для использования их методов
            $this->load->model('catalog/product'); //подключаем любую модель из OpenCart
            $this->load->model('catalog/category');

            // получаем все категории в которых есть этот продукт
            $product_categories_info = $this->model_catalog_product->getCategories($product_id);


            $i = 0;
            // перебираем эти категории
            foreach ($product_categories_info as $product_category_item) {
                $category_id = $product_category_item['category_id'];
                $category_info = $this->model_catalog_category->getCategory($category_id);

                $data['content'][$i] = [];
                $data['content'][$i][] = ['name' => $category_info['name']];

                $arr_categories_id = [];
                $arr_categories_id[] = $category_id;

                // находим все родительские категории
                while ($category_info['parent_id'] != 0) {
                    $category_info = $this->model_catalog_category->getCategory($category_info['parent_id']);
                    $data['content'][$i][] = ['name' => $category_info['name']];
                    $arr_categories_id[] = $category_info['category_id'];
                }

                // переворачиваем массив для созздания ссылок на каждую категорию и подкатегорию
                $arr_categories_id = array_reverse($arr_categories_id);

                // перебираем категории и дописываем ссылки к категориям
                foreach ($data['content'][$i] as &$item) {
                    $item['href'] = $this->url->link('product/category', 'path=' . implode('_', $arr_categories_id));
                    // удаляем последний id в массиве
                    array_pop($arr_categories_id);
                }

                $data['content'][$i]=array_reverse($data['content'][$i]);
                $i++;
            }
        }


        //стандартная процедура для контроллеров OpenCart, выбираем файл представления модуля для вывода данных
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mymodul.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/mymodul.tpl', $data);
        } else {
            return $this->load->view('default/template/module/mymodul.tpl', $data);
        }

    }
} ?>