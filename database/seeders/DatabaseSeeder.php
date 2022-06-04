<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Yoga Andrian',
            'username' => 'yogandrn',
            'email' => 'yoga@gmail.com',
            'phone' => '081540988167',
            'gender' => 'Not set',
            'roles' => 'USER',
            'password' => bcrypt('password'),
        ]);

        Product::create([
            'title' => 'Mouse Anti Radiasi',
            'description' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores magni, autem consequuntur odio error, dolorem illo, veniam rem eos quo nostrum dolorum amet aliquid neque nulla eligendi aut officiis! Nihil corporis porro incidunt alias aliquam voluptas perspiciatis explicabo aspernatur consequuntur!",
            'materials' => 'Alluminium',
            'price' => 125000, 
            'hg_beli' => 115000, 
            'stock' => 16,
            'weight' => 500,
            'image' => 'https://ducttapeanddenim.com/wp-content/uploads/2016/01/blog-12-craft-supplies-to-throw-away-SQUARE.jpg'
        ]);

        Product::create([
            'title' => 'Botol Minum Unlimited',
            'description' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores magni, autem consequuntur odio error, dolorem illo, veniam rem eos quo nostrum dolorum amet aliquid neque nulla eligendi aut officiis! Nihil corporis porro incidunt alias aliquam voluptas perspiciatis explicabo aspernatur consequuntur!",
            'materials' => 'Melanium',
            'price' => 56000,
            'hg_beli' => 49000,
            'stock' => 99,
            'weight' => 400,
            'image' => 'https://mykidcraft.com/images/basket5.jpg'
        ]);

        Product::create([
            'title' => 'Sajadah Terbang',
            'description' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores magni, autem consequuntur odio error, dolorem illo, veniam rem eos quo nostrum dolorum amet aliquid neque nulla eligendi aut officiis! Nihil corporis porro incidunt alias aliquam voluptas perspiciatis explicabo aspernatur consequuntur!",
            'materials' => 'Kain Sutra',
            'price' => 150000,
            'hg_beli' => 135000,
            'stock' => 6,
            'weight' => 2000,
            'image' => 'https://yt3.ggpht.com/a/AATXAJxf2lDsy0CDd-8VQP-FnqiCtS3p2ckhf5KNJQ=s900-c-k-c0xffffffff-no-rj-mo'
        ]);
        Product::create([
            'title' => 'Obat Anti Miskin',
            'description' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores magni, autem consequuntur odio error, dolorem illo, veniam rem eos quo nostrum dolorum amet aliquid neque nulla eligendi aut officiis! Nihil corporis porro incidunt alias aliquam voluptas perspiciatis explicabo aspernatur consequuntur!",
            'materials' => 'Alluminium',
            'price' => 1000000,
            'hg_beli' => 995000,
            'stock' => 1,
            'weight' => 1000,
            'image' => 'https://yt3.ggpht.com/a/AGF-l7-uFOeU1yldsKNquUp1rfEOwGmQ5qZlR_hWOQ=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        Gallery::create([
            'id_product' => 1,
            'image' => 'https://yt3.ggpht.com/a/AGF-l7-uFOeU1yldsKNquUp1rfEOwGmQ5qZlR_hWOQ=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        Gallery::create([
            'id_product' => 1,
            'image' => 'https://yt3.ggpht.com/a/AATXAJxf2lDsy0CDd-8VQP-FnqiCtS3p2ckhf5KNJQ=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        Gallery::create([
            'id_product' => 1,
            'image' => 'https://mykidcraft.com/images/basket5.jpg'
        ]);

        Gallery::create([
            'id_product' => 2,
            'image' => 'https://ducttapeanddenim.com/wp-content/uploads/2016/01/blog-12-craft-supplies-to-throw-away-SQUARE.jpg'
        ]);

        Gallery::create([
            'id_product' => 2,
            'image' => 'https://yt3.ggpht.com/a/AATXAJwKI16PEBYzbXMjsAnaUQvJn4x7Qi8wJwIl9A=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        Gallery::create([
            'id_product' => 2,
            'image' => 'https://yt3.ggpht.com/a-/AAuE7mD1adOOtcYXdVadJhQIw6Q30bnx3YCZqjugdQ=s900-mo-c-c0xffffffff-rj-k-no'
        ]);

        Gallery::create([
            'id_product' => 2,
            'image' => 'https://yt3.ggpht.com/a-/AAuE7mCg0WF1tgfQMpvSvIVcS5GLkib-xSH3y2wHQA=s900-mo-c-c0xffffffff-rj-k-no'
        ]);

        Gallery::create([
            'id_product' => 3,
            'image' => 'https://yt3.ggpht.com/a/AATXAJw1ToFsjMCfAsEL84nuBVrxlBzZ_pHWP378Nw=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        Gallery::create([
            'id_product' => 3,
            'image' => 'https://2.bp.blogspot.com/-GDGr7eNu0mM/UrsJmXbrpgI/AAAAAAAAC1Y/sdG_0UmYELI/s1600/grand_theft_auto_san_andreas_saleen_s7_twin_turbo_drawingX.JPG'
        ]);

        Gallery::create([
            'id_product' => 3,
            'image' => 'https://4.bp.blogspot.com/-M2PlWZFhByY/T3CtbYBWRCI/AAAAAAAAAC8/94Vt6D9gHqE/s1600/gtasa_vehicles_map.png'
        ]);

        Gallery::create([
            'id_product' => 4,
            'image' => 'https://yt3.ggpht.com/a/AATXAJwUKExLkd7IKhM9dSA4Tv5Xf7uIrL6fdh7y9Q=s900-c-k-c0xffffffff-no-rj-mo'
        ]);

        
    }
}
