-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 09:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectbackend`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`, `website`, `logo`, `color`) VALUES
(1, 'Inmotion', 'https://www.inmotionworld.com/', 'https://www.inmotionworld.com/static/upload/image/20230627/1687852016107481.png', 'ea6519'),
(2, 'Kingsong', 'https://kingsong.com/', 'https://kingsong.com/wp-content/uploads/2024/03/logo.png', 'ed672b'),
(3, 'Leaperkim', 'https://www.leaperkim.com/', 'https://www.out-fun.com/img/m/180.jpg', '07409a'),
(5, 'Begode', 'https://begode-europe.com/', 'https://begode-europe.com/coagroac/2021/01/BEGODE-logo-3.png', 'b90101'),
(6, 'Nosfet', 'https://www.nosfet.com/', 'https://static.wixstatic.com/media/88937f_10b7e19f1b4c4c369e19bc24b36cc660~mv2.png/v1/fit/w_2500,h_1330,al_c/88937f_10b7e19f1b4c4c369e19bc24b36cc660~mv2.png', '000bd8');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'monowheel', 'A monowheel is a single-wheel vehicle where the rider sits either inside or next to the wheel, controlling its movement. Unlike traditional bicycles or motorcycles, monowheels have just one large wheel and are often powered by pedals, electric motors, or small combustion engines. The rider balances and steers by shifting their weight, and the design makes for a unique, futuristic look. While monowheels have existed in various forms since the 19th century, modern versions are typically electric, used for urban commuting, and prized for their compactness and novelty.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `brand_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `title`, `description`, `price`, `stock`, `category_id`, `created_at`, `brand_id`) VALUES
(3, 'V14 50s', 'The Inmotion V14 Adventure electric unicycle is a high-performance and rugged personal transportation device designed for off-road adventures. With its 16-inch wheel diameter and 3-inch wide all-terrain tire, this unicycle offers excellent stability and traction on various surfaces.\r\n\r\nThis version of Inmotion V14 will be supplied with new Samsung 50S cells, that have a higher discharge rate and are considered safer option for agresive riders.\r\n\r\nEquipped with a powerful 4000-watt motor, the Inmotion V14 Adventure provides smooth and effortless acceleration, capable of reaching a top speed of 70 km/h. The motor also boasts a peak power output of 9000 watts, ensuring ample power for demanding off-road scenarios.\r\n\r\nThe unicycle is powered by a high-capacity 2400Wh battery, allowing for an impressive range of up to 120 km on a single charge. With such a large battery capacity, riders can confidently explore the outdoors without worrying about running out of power.\r\n\r\nWeighing 39 kg, the Inmotion V14 Adventure strikes a balance between portability and stability. Its robust construction and high-quality materials ensure durability and reliability even in challenging conditions.\r\n\r\nThe unicycle features a pneumatic suspension system, providing a comfortable and smooth ride over uneven terrain. This suspension system, along with the 16-inch diameter wheel, allows the V14 Adventure to tackle inclines of up to 50 degrees with ease.\r\n\r\n', 3599.00, 50, 1, '2024-11-13 21:10:18', 1),
(4, 'S18', 'Kingsong S18 is an electric unicycle with 2200W motor power and 1110 Wh battery. But most importantly with suspension... and great design, and impressive ergonomics.\r\n\r\nKingSong S18 has unique suspension with free movement of 90 mm. If you wish, you can block the suspension and it will become almost as regular EUC.\r\n\r\nBut who really wants that? Suspension was a success on EUC\'s and Kingsong S18 is a living example. You can drive it in active, almost agressive manner and yet have great comfort. This the EUC to get if you have problems with your back or your knees, as it really takes most of the roadbums away from you. And this is out of the personal experience.\r\n\r\n \r\n\r\nOf course you have your stadart Kingsong options like telescopic handle for ease of walking, good light with different dim options, fast charger 2,5A and rear brake signal. Unfortunately no bluetoth speakers inbuilt, but  who use them anyway.', 1799.00, 50, 1, '2024-11-14 07:55:58', 2),
(5, 'Patton S 50s', 'The Leaperkim Patton-S represents a significant evolution of original Leaperkim Patton - it now has 50S cells as standart, more durable construction and at the same time lighter weight. Overal design and feel is very similar to its predecesor, but a lot of small touches, from BMS to the different tire, make it a much awaited improvement.\r\nThe Patton-S stands out as a versatile, high-performance electric unicycle that\'s equally capable in urban environments and off-road conditions. Its combination of powerful motor, sophisticated suspension system, and comprehensive safety features makes it suitable for both daily commuting and adventure riding. The integration of 50S battery cells and lighter construction improves upon the original Patton\'s design, while maintaining the robust performance that users expect from the series.', 3399.00, 50, 1, '2024-11-14 07:55:58', 3),
(6, 'Apex', 'The Nosfet Apex is a new and powerful entrant in the electric unicycle market, offering impressive specifications that position it among the top high-performance models. With a free spin speed of 125 km/h, it’s built for users who seek speed and control. The battery pack features a robust Samsung INR21700 50S with a capacity of 151.2V and 2700Wh, ensuring extended ride times and reliable power. The Apex also offers efficient charging options with a 151.2V 5A charger, reducing downtime with a 4-hour charge at 5A or a rapid 2-hour charge at 10A. This unicycle can support a maximum rider weight of 120 kg and operates within a temperature range of -10ºC to 80ºC, making it suitable for diverse riding conditions.\r\n\r\nEquipped with advanced components, the Nosfet Apex features two types of suspension—an air shock measuring 205x62 and a coil shock at 220x66—providing a smooth ride even on challenging terrains. The Dual Board controller with 36pc MOS and a maximum current of 840A ensures responsive handling and control, while the headlight and dual taillight modes (slow brake/emergency brake) enhance safety, especially during night rides. At a net weight of 38.8 kg, this unicycle is designed for those seeking both portability and performance. \r\n\r\nAs a high-power model, the Nosfet Apex competes with well-established unicycles like the Leaperkim Lynx, Begode ET MAX, and Kingsong F22. With a peak motor power of 8000W and a nominal 3200W output, the Apex delivers powerful acceleration and consistent performance. The magnesium alloy rim and durable 2.75-14 tire ensure a smooth and controlled ride. The model also includes an informative display showing essential ride data such as speed, voltage, mileage, and battery status, giving riders full control over their experience. Nosfet is positioning itself as a strong competitor in the electric unicycle market, providing enthusiasts with a premium option for power and versatility.', 3800.00, 50, 1, '2024-11-14 07:55:58', 6),
(7, 'Mten Mini', 'The Begode Mten Mini is a sleek and ultra-portable electric unicycle, perfect for young riders and urban commuters who need a compact, reliable mode of transportation. Featuring a powerful enough 500W motor, the Mten Mini delivers a top speed of 20 kmh and is equipped with either a 98Wh or 180Wh battery option, providing a range of up to 10 kilometers on a single charge​\r\n\r\nWeighing just 11.75 kg, the Mten Mini is one of the lightest electric unicycles available, making it easy to carry and store in small spaces such as a backpack or under a desk​ ​. Its 11-inch tire good maneuvrebility, while the 2000-lumen headlight enhances visibility for safe nighttime riding​​.\r\n\r\nDesigned with user convenience in mind, the Mten Mini features a handle for easy handling and is compatible with the Begode app for real-time monitoring and customization​  ​. The robust build and recessed lighting add to its durability, making it an excellent choice for both beginners and seasoned riders looking for a fun, agile, and highly portable electric unicycle.\r\n\r\nExperience the freedom of effortless travel with the Begode Mten Mini, the ideal companion for short commutes and recreational rides, available now at an incredible price.', 599.00, 50, 1, '2024-11-14 07:55:58', 5),
(8, 'V8F', 'When you ask someone what EUC they learned on, there\'s a good chance they say the InMotion V8 or V8F. This version, the V8F, features a powerful 1000w motor that can reach speeds up to 35km/h. More than enough in this small package. \r\n\r\nThe 512Wh battery get up to 25km of Real Range per charge. The V8F is the perfect choice for your first wheel, as a companion wheel to your larger EUC, and the best way to go pick up your morning coffee!\r\n\r\n', 999.00, 50, 1, '2024-11-17 22:59:15', 1),
(9, 'S16 pro', 'Slick, stylin’, suspension, sixteen inches - the King Song S16 Pro is here to take your daily riding from chore to sheer delight. Smaller than the S22 Pro, more powerful than the S18, the S16 Pro slips right into that perfect pocket of giving you everything you need for an uncomplicated day of riding without piling on all the crazy performance that many riders simply don’t need.\r\n\r\nThe 3000W motor is plenty enough to churn up your city’s hills and cruise the straightaways with ease. Impressive speed for its size, the S16 Pro will get you places with a top speed of 60 km/h while the 1480Wh Samsung 50S battery gives you a tidy 50-65km of RealRange depending on rider weight and conditions. All this being smooth as butter on the 88MM of travel, centre mounted 750lb spring suspension system.\r\n\r\nDo you like extras? Let’s talk about extras. Looking good is feeling good and you’ll be pulling all the looks as your wraparound LED light system pulses to your tunes popping out of the 4 speaker system (which isn\'t bad!). Unique accented LED headlights light your ride as you cruise the twilight hours away. And hang on tight with the spiked pedals and customize your power pad situation thanks to the flat panel design. The King Song S16 Pro is your perfect first wheel, and an even better complimentary ride to your performance wheel. ', 2199.00, 50, 1, '2024-11-18 09:06:53', 2),
(10, 'Blitz', 'The Begode Blitz redefines performance with its lightweight design, shedding 15.7 lbs (7 kg) compared to its predecessor, the Master V4. Weighing in at just 79.1 lbs (36 kg), this electric unicycle is engineered for speed. The reduced weight is achieved through the extensive use of magnesium alloy for the motor, battery boxes, and other structural components. It’s the lightest machine in its class without compromising strength.', 3099.00, 50, 1, '2024-11-18 09:06:53', 5),
(11, 'V13 PRO', NULL, 3299.00, 0, NULL, '2024-11-19 22:30:01', 1),
(12, 'F22 PRO', 'The Kingsong F22 Pro Electric Unicycle is a cutting-edge personal mobility device designed for thrill-seekers and urban commuters. Whether you want to conquer steep hills or cruise through city streets, the KS-F22 Pro delivers unparalleled performance and customization options.', 3999.00, 0, 1, '2024-11-20 08:01:39', 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `image_url`, `alt_text`, `created_at`) VALUES
(1, 3, 'https://onewheelspot.com/wp-content/uploads/2024/10/V14.870_2.webp', 'Inmotion V14 50S side view', '2024-11-13 21:43:35'),
(2, 3, 'https://i0.wp.com/atomicev.co.nz/wp-content/uploads/2024/06/InmotionV14-50Sbattery.webp?fit=1200%2C1200&ssl=1', 'Inmotion V14 50S Side front profile\r\n', '2024-11-13 21:44:34'),
(3, 3, 'https://iwheel.se/cdn/shop/files/inmotion-v14-50s-enhjulingar-928_2048x2048.webp?v=1725823275', 'Inmotion V14 50S Side rear view', '2024-11-14 07:35:38'),
(4, 3, 'https://eunicycle.com.au/cdn/shop/files/mmexport1701862396195_1024x1024.png?v=1715144552', 'Inmotion v14 50s front view', '2024-11-14 07:36:37'),
(5, 3, 'https://houston.renteboards.com/cdn/shop/files/V14-11_900x.png?v=1716572145', 'Inmotion V14 50s rear view', '2024-11-14 07:37:30'),
(6, 4, 'https://eevees.com/cdn/shop/files/KingSong_S18_1_01f454c6-31fa-42b7-928b-12d04567b4dd.png?v=1685145424&width=1200', 'Kingsong S18 side view', '2024-11-14 20:41:46'),
(7, 4, 'https://iroll.co.za/cdn/shop/files/kingsongS18.webp?v=1722096541', 'Kingsong S18 side front view', '2024-11-14 20:41:46'),
(8, 4, 'https://ae01.alicdn.com/kf/S605ffc8c6a0c4720b196fcefd2bdc99en/Koningsong-S18-Nieuwe-Parkeerbeugel-Elektrische-Eenwieler-Accessoires-Ks-S18-Parkeerbeugel-Euc-Onderdelen.png', 'Kingsong S18 side rear view', '2024-11-14 20:41:46'),
(9, 4, 'https://ideacdn.net/idea/ct/07/myassets/products/089/ks-s18-xiang-xiao-hei-3.png?revision=1724419850', 'Kingsong S18 front view', '2024-11-14 20:41:46'),
(10, 4, 'https://ideacdn.net/idea/ct/07/myassets/products/089/ks-s18-xiang-xiao-hei-4_min.png?revision=1724419850', 'Kingsong S18 rear view', '2024-11-14 20:41:46'),
(11, 5, 'https://i0.wp.com/www.neverwheelup.com/wp-content/uploads/2019/09/VeteranPatton_1.webp?fit=1080%2C1080&ssl=1', 'Leaperkim Veteran Patton side view', '2024-11-14 21:17:23'),
(12, 5, 'https://eevees.com/cdn/shop/files/VeteranPatton_2.png?v=1684889802&width=1200', 'Leaperkim Veteran Patton side front view', '2024-11-14 21:17:23'),
(13, 5, 'https://eevees.com/cdn/shop/files/VeteranPatton_5.png?v=1684889802&width=1200', 'Leaperkim Veteran Patton side rear view', '2024-11-14 21:17:23'),
(14, 5, 'https://noaio.com/static/uploads/veteran-pa-1679921858887.png', 'Leaperkim Veteran Patton front view', '2024-11-14 21:17:23'),
(15, 5, 'https://eevees.com/cdn/shop/files/VeteranPatton_3.png?v=1684889802&width=1199', 'Leaperkim Veteran Patton rear view', '2024-11-14 21:17:23'),
(16, 6, 'https://eevees.com/cdn/shop/files/NOSFET_Apex_1.png?v=1729198089&width=2000', 'Nosfet APEX side view', '2024-11-14 21:27:56'),
(17, 6, 'https://eevees.com/cdn/shop/files/NOSFET_Apex_4.png?v=1729198090&width=2000', 'Nosfet APEX side front view', '2024-11-14 21:27:56'),
(18, 6, 'https://eevees.com/cdn/shop/files/NOSFET_Apex_7.png?v=1729198090&width=2000', 'Nosfet APEX side rear view', '2024-11-14 21:27:56'),
(19, 6, 'https://eevees.com/cdn/shop/files/NOSFET_Apex_3.png?v=1729198090&width=1080', 'Nosfet APEX front view', '2024-11-14 21:27:56'),
(20, 6, 'https://eevees.com/cdn/shop/files/NOSFET_Apex_5.png?v=1729198089&width=1080', 'Nosfet APEX rear view', '2024-11-14 21:27:56'),
(21, 7, 'https://eevees.com/cdn/shop/files/Begode_MTen5_1.png?v=1724457967&width=1080', 'Mten Mini side view', '2024-11-14 21:44:39'),
(22, 7, 'https://eevees.com/cdn/shop/files/Begode_MTen5_4.png?v=1724457967&width=1080', 'Mten Mini side front view', '2024-11-14 21:44:39'),
(23, 7, 'https://eevees.com/cdn/shop/files/Begode_MTen5_3.png?v=1724457967&width=1080', 'Mten Mini side rear view', '2024-11-14 21:44:39'),
(24, 7, 'https://eevees.com/cdn/shop/files/Begode_MTen5_2.png?v=1724457967&width=1080', 'Mten Mini front view', '2024-11-14 21:44:39'),
(25, 7, 'https://wheelriders.dk/wp-content/uploads/2024/06/Begode-Mten-Mini.webp', 'Mten Mini rear view', '2024-11-14 21:44:39'),
(26, 8, 'https://boostedusa.com/cdn/shop/products/BoostedUSA-Inmotion-Electric-Unicycle-V8-V8F-Profile-02_1250x.png?v=1668033375', 'Inmotion V8F side view', '2024-11-17 23:12:34'),
(27, 8, 'https://alienrides.com/cdn/shop/files/matt_1024x1024.webp?v=1722461872', 'Inmotion V8F side front view', '2024-11-17 23:12:34'),
(28, 8, 'https://eevees.com/cdn/shop/files/InMotionV8F_2.png?v=1684879656&width=1080', 'Inmotion V8F side rear', '2024-11-17 23:12:34'),
(29, 8, 'https://eevees.com/cdn/shop/files/InMotionV8F_4.png?v=1684879656&width=1080', 'Inmotion V8F top view', '2024-11-17 23:12:34'),
(30, 8, 'https://eevees.com/cdn/shop/files/InMotionV8F_3.png?v=1684879656&width=1080', 'Inmotion V8F rear view', '2024-11-17 23:12:34'),
(31, 9, 'https://eevees.com/cdn/shop/files/KingSongS16_1.png?v=1700693841&width=1080', 'Kingsong S16 pro side view', '2024-11-18 09:33:12'),
(32, 9, 'https://eevees.com/cdn/shop/files/KingSongS16_3.png?v=1700693841&width=1080', 'Kingsong S16 pro side front view', '2024-11-18 09:33:12'),
(33, 9, 'https://eevees.com/cdn/shop/files/KingSongS16_4.png?v=1700693841&width=1080', 'Kingsong S16 pro side rear view', '2024-11-18 09:33:12'),
(34, 9, 'https://www.euco.us/cdn/shop/files/S16.5_1024x1024@2x.png?v=1705430079', 'Kingsong S16 pro front view', '2024-11-18 09:33:12'),
(35, 9, 'https://eevees.com/cdn/shop/files/KingSongS16_2.png?v=1700693841&width=1080', 'Kingsong S16 pro rear view', '2024-11-18 09:33:12'),
(36, 10, 'https://alienrides.com/cdn/shop/files/etmax_1024x1024.png?v=1709499455', 'Begode Blitz side view', '2024-11-18 09:33:12'),
(37, 10, 'https://ae04.alicdn.com/kf/S17fc3b86ed7e43c3ab7ed4882305debfN.png', 'Begode Blitz side front view', '2024-11-18 09:33:12'),
(38, 10, 'https://ae04.alicdn.com/kf/S3dda58ff566b4e41867c0c1b077d1960B.png', 'Begode Blitz side rear view', '2024-11-18 09:33:12'),
(39, 10, 'https://ae04.alicdn.com/kf/S3d6116d8ffd34fba9e424de123f1ff5dt.png', 'Begode Blitz screen view', '2024-11-18 09:35:38'),
(40, 11, 'https://cdn.shopify.com/s/files/1/2272/3277/files/V13Pro-4_1000x1500.png?v=1716926702', 'Inmotion V13 pro side view', '2024-11-19 22:30:01'),
(41, 11, 'https://cdn.shopify.com/s/files/1/2272/3277/files/V13Pro-5_1_1000x1500.png?v=1716926669', 'Inmotion V13 pro side \nfront \nview', '2024-11-19 22:30:01'),
(42, 11, 'https://cdn.shopify.com/s/files/1/2272/3277/files/V13Pro-6_1000x1500.png?v=1716926686', 'Inmotion V13 proside  rear view', '2024-11-19 22:30:01'),
(43, 11, 'https://cdn.shopify.com/s/files/1/2272/3277/files/V13Pro-9_1000x1500.png?v=1716926713', 'Inmotion V13 pro front view', '2024-11-19 22:30:01'),
(44, 11, 'https://cdn.shopify.com/s/files/1/2272/3277/files/V13Pro-10_1000x1500.png?v=1716926680', 'Inmotion V13 pro rear view', '2024-11-19 22:30:01'),
(45, 12, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5SW6cTd6yoz9yiwm3VPI8EAGR5ZKy6kgdVQ&s', 'Kingsong F22 PRO side view', '2024-11-20 08:01:39'),
(46, 12, 'https://atomicev.co.nz/wp-content/uploads/2024/10/KingSongF22Pro-1-scaled.webp', 'Kingsong F22 PRO side front view', '2024-11-20 08:01:39'),
(47, 12, 'https://i0.wp.com/atomicev.co.nz/wp-content/uploads/2024/10/KingSongF22Pro-4-scaled.webp?fit=2560%2C2560&ssl=1', 'Kingsong F22 PRO top view', '2024-11-20 08:01:39'),
(48, 12, 'https://i0.wp.com/atomicev.co.nz/wp-content/uploads/2024/10/KingSongF22Pro-2-scaled.webp?fit=2560%2C2560&ssl=1', 'Kingsong F22 PRO front view', '2024-11-20 08:01:39'),
(49, 12, 'https://i0.wp.com/atomicev.co.nz/wp-content/uploads/2024/10/KingSongF22Pro-5light-scaled.webp?fit=2560%2C2560&ssl=1', 'Kingsong F22 PRO rear view', '2024-11-20 08:01:39');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `specifications`
--

CREATE TABLE `specifications` (
  `specification_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `motor_power` int(11) DEFAULT NULL,
  `top_speed` decimal(5,2) DEFAULT NULL,
  `battery_capacity` int(11) DEFAULT NULL,
  `range_per_charge` int(11) DEFAULT NULL,
  `charging_time` decimal(4,2) DEFAULT NULL,
  `wheel_size` decimal(4,1) DEFAULT NULL,
  `weight_capacity` int(11) DEFAULT NULL,
  `incline_capability` decimal(4,1) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `pedal_height` decimal(4,1) DEFAULT NULL,
  `tire_type` varchar(50) DEFAULT NULL,
  `suspension` varchar(50) DEFAULT NULL,
  `ip_rating` varchar(10) DEFAULT NULL,
  `speaker_system` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specifications`
--

INSERT INTO `specifications` (`specification_id`, `product_id`, `motor_power`, `top_speed`, `battery_capacity`, `range_per_charge`, `charging_time`, `wheel_size`, `weight_capacity`, `incline_capability`, `weight`, `pedal_height`, `tire_type`, `suspension`, `ip_rating`, `speaker_system`) VALUES
(1, 3, 4000, 70.00, 2400, 120, 4.00, 16.0, 140, 50.0, 39.00, NULL, 'offroad', 'adaptive & progressive', 'IPX6', 'no'),
(2, 4, 2200, 50.00, 1110, 70, 6.00, 18.0, 120, NULL, 25.00, 110.0, 'street', 'active', 'IP55', 'no'),
(3, 5, 3000, 80.00, 2220, 130, 4.00, 16.0, 120, 45.0, 39.00, 220.0, 'offroad', 'yes', NULL, 'no'),
(4, 6, 3200, 90.00, 2700, 80, 4.00, 14.0, 120, 45.0, 38.00, 250.0, 'street', 'progressive linkage', 'IPX6', NULL),
(5, 7, 500, 20.00, 180, 15, 3.00, 11.0, 70, NULL, 12.00, 12.0, 'street', 'no', NULL, 'no'),
(6, 8, 1000, 35.00, 518, 25, 4.00, 16.0, 100, 25.0, 15.00, 17.0, 'street', 'none', NULL, NULL),
(8, 9, 2800, 60.00, 1480, 130, 4.50, 16.0, 120, 48.0, 33.30, 220.0, 'offroad', 'spring', NULL, 'yes'),
(9, 10, 3500, 85.00, 2400, 120, 3.00, 20.0, 130, 45.0, 36.00, 243.0, 'racing', 'dual damping air ', 'IP67', NULL),
(10, 11, 4500, 100.00, 3024, 140, 6.00, 22.0, 120, 45.0, 50.00, 220.0, 'street', 'air suspension', 'no', 'no'),
(11, 12, 5000, 80.00, 2738, 160, 3.00, 18.0, 120, 40.0, 45.00, 230.0, 'offroad', 'Fast ace', 'IPX6', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,2) DEFAULT 1000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `fname`, `lname`, `role`, `created_at`, `balance`) VALUES
(13, 'admin@admin.com', '$2y$12$AMFrgtqVS0c81r0ZZ0dNFOf83BR3CxpgbHmG3kC69N0uCGa6ECQxm', 'admin', 'admin', 'admin', '2024-11-13 13:08:50', 1000.00),
(14, 'test@test.com', '$2y$12$xVWiCSJyd8lwQxvYCHGHpOy40Hp1XS8iZXlcw8aX47j4YAKnC30cG', 'test', 'test', 'user', '2024-11-13 13:08:50', 1000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_brand` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `specifications`
--
ALTER TABLE `specifications`
  ADD PRIMARY KEY (`specification_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specifications`
--
ALTER TABLE `specifications`
  MODIFY `specification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_brand` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `specifications`
--
ALTER TABLE `specifications`
  ADD CONSTRAINT `specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
