<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\RestaurantTable;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Étterem 1
        $restaurant1 = Restaurant::create([
            'name' => 'Pizza Palazzo',
            'description' => 'Tradicionális olasz pizzéria autentikus receptekkel',
            'address' => 'Budapest, Andrássy út 45.',
            'phone' => '+36 1 234 5678',
            'email' => 'info@pizzapalazzo.hu',
            'image_url' => 'http://localhost:8000/images/pizza-palazzo.png',
            'rating' => 4.8,
            'delivery_time' => 30,
            'delivery_fee' => 3.99,
            'is_open' => true,
            'opening_time' => '11:00',
            'closing_time' => '23:00',
        ]);

        // Pizzák kategória
        $pizzaCategory = MenuCategory::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Pizzák',
            'description' => 'Kövön sütött, autentikus olasz pizzák friss hozzávalókkal',
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Margherita',
            'description' => 'San Marzano paradicsomszósz, friss mozzarella di bufala, extra szűz olívaolaj, friss bazsalikom',
            'price' => 2490,
            'image_url' => 'https://www.themealdb.com/images/media/meals/x0lk931587671540.jpg',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 342,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Pepperoni',
            'description' => 'Paradicsomszósz, mozzarella, pikáns american pepperoni szalami, oregánó',
            'price' => 2890,
            'image_url' => 'https://www.themealdb.com/images/media/meals/wf49qs1763075222.jpg',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 518,
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Quattro Formaggi',
            'description' => 'Négy válogatott sajt: mozzarella, gorgonzola, pecorino romano, ricotta – fokhagymás alap',
            'price' => 3190,
            'image_url' => 'http://localhost:8000/images/Quattro Formaggi.jpg',
            'preparation_time' => 15,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 214,
            'order' => 3,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Diavola',
            'description' => 'Paradicsomszósz, mozzarella, csípős nduja szalámi, calabriai chilipaprika, fekete olajbogyó',
            'price' => 2990,
            'image_url' => 'https://www.themealdb.com/images/media/meals/lrfdwz1764438393.jpg',
            'preparation_time' => 13,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 189,
            'order' => 4,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Prosciutto e Funghi',
            'description' => 'Paradicsomszósz, mozzarella, San Daniele pármai sonka, erdei gombák, rucola',
            'price' => 3290,
            'image_url' => 'https://www.themealdb.com/images/media/meals/sutysw1468247559.jpg',
            'preparation_time' => 14,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 276,
            'order' => 5,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Tonno e Cipolla',
            'description' => 'Paradicsomszósz, mozzarella, tonhal, vöröshagyma, kapribogyó, oregánó',
            'price' => 2890,
            'image_url' => 'https://www.themealdb.com/images/media/meals/llcbn01574260722.jpg',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.5,
            'rating_count' => 143,
            'order' => 6,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'BBQ Csirke',
            'description' => 'BBQ szósz alap, mozzarella, grillezett csirkemell, lilahagyma, édes kukorica, füstölt sajt',
            'price' => 3090,
            'image_url' => 'https://www.themealdb.com/images/media/meals/qptpvt1487339892.jpg',
            'preparation_time' => 15,
            'is_available' => true,
            'rating' => 4.6,
            'rating_count' => 301,
            'order' => 7,
        ]);

        MenuItem::create([
            'category_id' => $pizzaCategory->id,
            'name' => 'Vegetariana',
            'description' => 'Paradicsomszósz, mozzarella, grillezett paprika, cukkinni, padlizsán, koktélparadicsom, friss bazsalikom',
            'price' => 2690,
            'image_url' => 'https://www.themealdb.com/images/media/meals/x0lk931587671540.jpg',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.4,
            'rating_count' => 98,
            'order' => 8,
        ]);

        // Pasta kategória
        $pastaCategory = MenuCategory::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Pasta',
            'description' => 'Frissen gyúrt olasz pasta hagyományos szószokkal',
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $pastaCategory->id,
            'name' => 'Spaghetti Carbonara',
            'description' => 'Friss spagetti, guanciale (sertéspofaszalonna), tojássárgája, pecorino romano, fekete bors',
            'price' => 2790,
            'image_url' => 'https://www.themealdb.com/images/media/meals/llcbn01574260722.jpg',
            'preparation_time' => 10,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 412,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $pastaCategory->id,
            'name' => 'Tagliatelle Bolognese',
            'description' => 'Tojásos tagliatelle, lassú főzésű marhahúsos ragú, San Marzano paradicsom, vörösbor, parmezán',
            'price' => 2890,
            'image_url' => 'https://www.themealdb.com/images/media/meals/sutysw1468247559.jpg',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 387,
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $pastaCategory->id,
            'name' => 'Penne Arrabiata',
            'description' => 'Penne rigate, paradicsomszósz, fokhagyma, friss chilipaprika, petrezselyem, parmezán',
            'price' => 2490,
            'image_url' => 'https://www.themealdb.com/images/media/meals/llcbn01574260722.jpg',
            'preparation_time' => 10,
            'is_available' => true,
            'rating' => 4.5,
            'rating_count' => 201,
            'order' => 3,
        ]);

        // Előételek kategória
        $starterCategory = MenuCategory::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Előételek',
            'description' => 'Hagyományos olasz antipasti',
            'order' => 3,
        ]);

        MenuItem::create([
            'category_id' => $starterCategory->id,
            'name' => 'Bruschetta al Pomodoro',
            'description' => 'Pirított ciabatta, koktélparadicsom, fokhagyma, friss bazsalikom, extra szűz olívaolaj',
            'price' => 1290,
            'image_url' => 'https://www.themealdb.com/images/media/meals/xvsurr1511719182.jpg',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.6,
            'rating_count' => 178,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $starterCategory->id,
            'name' => 'Caprese Saláta',
            'description' => 'Friss mozzarella, érett paradicsom, bazsalikom, balzsamecet redukció, olívaolaj',
            'price' => 1690,
            'image_url' => 'https://www.themealdb.com/images/media/meals/wvqpwt1468339226.jpg',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 156,
            'order' => 2,
        ]);

        // Desszertek kategória
        $dessertCategory = MenuCategory::create([
            'restaurant_id' => $restaurant1->id,
            'name' => 'Desszertek',
            'description' => 'Klasszikus olasz édességek',
            'order' => 4,
        ]);

        MenuItem::create([
            'category_id' => $dessertCategory->id,
            'name' => 'Tiramisù',
            'description' => 'Hagyományos olasz desszert: ladyfinger, mascarpone krém, espresso, kakaópor',
            'price' => 1490,
            'image_url' => 'https://www.themealdb.com/images/media/meals/wvpsxx1468256321.jpg',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 523,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $dessertCategory->id,
            'name' => 'Panna Cotta',
            'description' => 'Vaníliás tejszín zselé, erdei gyümölcs coulis, friss bogyók',
            'price' => 1290,
            'image_url' => 'http://localhost:8000/images/Panna cotta.jfif',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 234,
            'order' => 2,
        ]);

        // Asztalok
        for ($i = 1; $i <= 5; $i++) {
            RestaurantTable::create([
                'restaurant_id' => $restaurant1->id,
                'table_number' => $i,
                'capacity' => $i <= 2 ? 2 : ($i <= 4 ? 4 : 6),
                'status' => 'available',
                'qr_code' => 'QR_' . $restaurant1->id . '_' . $i,
            ]);
        }

        // Étterem 2
        $restaurant2 = Restaurant::create([
            'name' => 'Szushi Paradise',
            'description' => 'Autentikus japán szushi és ramen',
            'address' => 'Budapest, Váci utca 12.',
            'phone' => '+36 1 987 6543',
            'email' => 'info@sushiparadise.hu',
            'image_url' => 'http://localhost:8000/images/szushi-paradise.png',
            'rating' => 4.9,
            'delivery_time' => 25,
            'delivery_fee' => 2.99,
            'is_open' => true,
            'opening_time' => '11:00',
            'closing_time' => '22:00',
        ]);

        // Szushi kategória
        $sushiCategory = MenuCategory::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Szushi',
            'description' => 'Friss szushi rollik és nigiri',
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'Philadelphia Roll',
            'description' => 'Lazac, túró, uborka, avokádó – 8 szelet',
            'price' => 2990,
            'image_url' => 'http://localhost:8000/images/Philadelphia Roll.jfif',
            'preparation_time' => 8,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 220,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'California Roll',
            'description' => 'Rák, avokádó, uborka, tobiko – 8 szelet',
            'price' => 2690,
            'image_url' => 'http://localhost:8000/images/California Roll.jfif',
            'preparation_time' => 8,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 180,
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'Dragon Roll',
            'description' => 'Tempura garnélarák, uborka belül, kívül avokádó és lazac, unagi szósz – 8 szelet',
            'price' => 3490,
            'image_url' => 'http://localhost:8000/images/Dragon Roll.jfif',
            'preparation_time' => 10,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 312,
            'order' => 3,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'Spicy Tuna Roll',
            'description' => 'Friss tonhal, sriracha majonéz, uborka, szezámmag – 8 szelet',
            'price' => 3190,
            'image_url' => 'http://localhost:8000/images/Spicy Tuna Roll.jfif',
            'preparation_time' => 8,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 267,
            'order' => 4,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'Sake Nigiri',
            'description' => 'Friss lazac szelet préselt rizsgolyón, wasabi, szójaszósz – 2 db',
            'price' => 1490,
            'image_url' => 'http://localhost:8000/images/Sake Nigiri.jfif',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 198,
            'order' => 5,
        ]);

        MenuItem::create([
            'category_id' => $sushiCategory->id,
            'name' => 'Rainbow Roll',
            'description' => 'California roll alap, tetején váltakozó lazac, tonhal, garnéla és avokádó szeletek – 8 szelet',
            'price' => 3790,
            'image_url' => 'http://localhost:8000/images/Rainbow Roll.jfif',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 445,
            'order' => 6,
        ]);

        // Ramen kategória
        $ramenCategory = MenuCategory::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Ramen',
            'description' => 'Meleg, finom ramen levesek',
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $ramenCategory->id,
            'name' => 'Tonkotsu Ramen',
            'description' => 'Sertéscsont alaplé, tojásos nudli, lágytojás, bambuszrügy, nori lap',
            'price' => 2890,
            'image_url' => 'http://localhost:8000/images/Tonkotsu Ramen.jfif',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.9,
            'rating_count' => 140,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $ramenCategory->id,
            'name' => 'Shoyu Ramen',
            'description' => 'Szójaszósz alapú csirkehúsleves, tojásos nudli, chashu sertés, menma, nori, lágytojás',
            'price' => 2690,
            'image_url' => 'http://localhost:8000/images/Shoyu Ramen.jfif',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 210,
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $ramenCategory->id,
            'name' => 'Miso Ramen',
            'description' => 'Miso paszta alapú leves, kukorica, vaj, bambuszrügy, lágytojás, nori lap',
            'price' => 2790,
            'image_url' => 'http://localhost:8000/images/Miso Ramen.jfif',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.7,
            'rating_count' => 175,
            'order' => 3,
        ]);

        MenuItem::create([
            'category_id' => $ramenCategory->id,
            'name' => 'Vegán Ramen',
            'description' => 'Kombu és shiitake alaplé, tofu, pak choi, kukorica, bambuszrügy, szézámolaj',
            'price' => 2590,
            'image_url' => 'http://localhost:8000/images/Vegán Ramen.jfif',
            'preparation_time' => 12,
            'is_available' => true,
            'rating' => 4.5,
            'rating_count' => 98,
            'order' => 4,
        ]);

        // Előételek kategória (Szushi Paradise)
        $sushiStarterCategory = MenuCategory::create([
            'restaurant_id' => $restaurant2->id,
            'name' => 'Előételek',
            'description' => 'Japán előételek és snackek',
            'order' => 3,
        ]);

        MenuItem::create([
            'category_id' => $sushiStarterCategory->id,
            'name' => 'Edamame',
            'description' => 'Párolt sózott szójabab, enyhe füstölt ízesítéssel',
            'price' => 890,
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/28/Edamame.jpg',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.5,
            'rating_count' => 134,
            'order' => 1,
        ]);

        MenuItem::create([
            'category_id' => $sushiStarterCategory->id,
            'name' => 'Gyoza',
            'description' => 'Sütött japán töltött gombóc sertéshússal és káposztával, ponzu mártással – 6 db',
            'price' => 1590,
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/40/Gyoza_Houhi.jpg',
            'preparation_time' => 8,
            'is_available' => true,
            'rating' => 4.8,
            'rating_count' => 289,
            'order' => 2,
        ]);

        MenuItem::create([
            'category_id' => $sushiStarterCategory->id,
            'name' => 'Miso Leves',
            'description' => 'Hagyományos japán miso leves tofuval, wakame algával és zöldhagymával',
            'price' => 990,
            'image_url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ef/Miso_soup.jpg',
            'preparation_time' => 5,
            'is_available' => true,
            'rating' => 4.6,
            'rating_count' => 203,
            'order' => 3,
        ]);

        // Asztalok
        for ($i = 1; $i <= 4; $i++) {
            RestaurantTable::create([
                'restaurant_id' => $restaurant2->id,
                'table_number' => $i,
                'capacity' => $i <= 2 ? 2 : 4,
                'status' => 'available',
                'qr_code' => 'QR_' . $restaurant2->id . '_' . $i,
            ]);
        }
    }
}

