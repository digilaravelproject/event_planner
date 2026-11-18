-- Five active catering vendors for the customer food question.
-- Safe to run repeatedly: only these five named demo vendors are replaced.
START TRANSACTION;

DELETE FROM vendors_dynamic
WHERE JSON_UNQUOTE(JSON_EXTRACT(vendor_json, '$.identity.name')) IN (
  'Royal Spice Caterers', 'Celebration Kitchen', 'Shree Satvik Caterers',
  'Deccan Biryani House', 'Coastal Feast Catering'
);

INSERT INTO vendors_dynamic (vendor_json, status, created_at, updated_at) VALUES
(
 JSON_OBJECT(
  'schema_version', 1, 'identity', JSON_OBJECT('name','Royal Spice Caterers','category','Catering'),
  'attributes', JSON_ARRAY(JSON_OBJECT('id',UUID(),'key','cuisines','label','Cuisines','type','multi_select','value',JSON_ARRAY('North Indian','Mughlai','Continental'),'position',0)),
  'food_menu_items', JSON_ARRAY(
   JSON_OBJECT('id','paneer_tikka','name','Paneer Tikka','category','Starters','price_per_person',220),
   JSON_OBJECT('id','hara_bhara_kebab','name','Hara Bhara Kebab','category','Starters','price_per_person',160),
   JSON_OBJECT('id','dal_makhani','name','Dal Makhani','category','Main Course','price_per_person',140),
   JSON_OBJECT('id','paneer_lababdar','name','Paneer Lababdar','category','Main Course','price_per_person',210),
   JSON_OBJECT('id','veg_biryani','name','Veg Biryani','category','Rice','price_per_person',180),
   JSON_OBJECT('id','rasmalai','name','Rasmalai','category','Desserts','price_per_person',90)
  ),
  'packages', JSON_ARRAY(
   JSON_OBJECT('id','royal_silver','name','Royal Silver','min_price',699,'max_price',799,'duration','6 hours','description','Classic vegetarian celebration menu','inclusions',JSON_ARRAY('2 starters','2 main courses','Dal and rice','Breads','1 dessert')),
   JSON_OBJECT('id','royal_gold','name','Royal Gold','min_price',899,'max_price',1049,'duration','8 hours','description','Premium multi-cuisine menu','inclusions',JSON_ARRAY('Welcome drink','3 starters','3 main courses','Live chaat','2 desserts')),
   JSON_OBJECT('id','royal_platinum','name','Royal Platinum','min_price',1199,'max_price',1399,'duration','10 hours','description','Luxury banquet experience','inclusions',JSON_ARRAY('Mocktail bar','4 starters','4 main courses','2 live counters','Dessert lounge'))
  ),
  'food_extras', JSON_ARRAY(JSON_OBJECT('id','chaat_counter','name','Live Chaat Counter','min_price',90,'max_price',120,'unit','per_plate','icon','fa-solid fa-bowl-food')),
  'media',JSON_OBJECT('images',JSON_ARRAY()), 'additional_details',JSON_ARRAY(),
  'seo',JSON_OBJECT('short_description','Premium North Indian wedding catering','description','Vendor-created menus and packages.','tags',JSON_ARRAY('catering'),'keywords',JSON_ARRAY('wedding catering'))
 ), 'active', NOW(), NOW()
),
(
 JSON_OBJECT(
  'schema_version',1,'identity',JSON_OBJECT('name','Celebration Kitchen','category','Catering'),'attributes',JSON_ARRAY(),
  'food_menu_items',JSON_ARRAY(
   JSON_OBJECT('id','bruschetta','name','Tomato Basil Bruschetta','category','Starters','price_per_person',190),
   JSON_OBJECT('id','cheese_balls','name','Cheese Corn Balls','category','Starters','price_per_person',175),
   JSON_OBJECT('id','white_sauce_pasta','name','White Sauce Pasta','category','Main Course','price_per_person',230),
   JSON_OBJECT('id','thai_curry','name','Thai Green Curry','category','Main Course','price_per_person',260),
   JSON_OBJECT('id','fried_rice','name','Burnt Garlic Fried Rice','category','Rice','price_per_person',185),
   JSON_OBJECT('id','tiramisu','name','Tiramisu Cup','category','Desserts','price_per_person',140)
  ),
  'packages',JSON_ARRAY(
   JSON_OBJECT('id','celebration_classic','name','Celebration Classic','min_price',749,'max_price',849,'duration','6 hours','description','Contemporary vegetarian buffet','inclusions',JSON_ARRAY('Welcome drink','2 starters','2 mains','Rice or noodles','Dessert')),
   JSON_OBJECT('id','celebration_signature','name','Celebration Signature','min_price',999,'max_price',1149,'duration','8 hours','description','Indian and continental spread','inclusions',JSON_ARRAY('Mocktails','3 starters','3 mains','Pasta counter','2 desserts')),
   JSON_OBJECT('id','celebration_luxe','name','Celebration Luxe','min_price',1299,'max_price',1499,'duration','10 hours','description','Luxury global cuisine buffet','inclusions',JSON_ARRAY('Beverage bar','4 starters','4 mains','2 live counters','Dessert studio'))
  ),
  'food_extras',JSON_ARRAY(JSON_OBJECT('id','pasta_counter','name','Live Pasta Counter','min_price',110,'max_price',150,'unit','per_plate','icon','fa-solid fa-utensils')),
  'media',JSON_OBJECT('images',JSON_ARRAY()),'additional_details',JSON_ARRAY(),'seo',JSON_OBJECT('short_description','Modern multi-cuisine catering','description','Vendor-created menus and packages.','tags',JSON_ARRAY('catering'),'keywords',JSON_ARRAY('continental catering'))
 ),'active',NOW(),NOW()
),
(
 JSON_OBJECT(
  'schema_version',1,'identity',JSON_OBJECT('name','Shree Satvik Caterers','category','Catering'),'attributes',JSON_ARRAY(),
  'food_menu_items',JSON_ARRAY(
   JSON_OBJECT('id','jain_paneer_tikka','name','Jain Paneer Tikka','category','Starters','price_per_person',230),
   JSON_OBJECT('id','khandvi','name','Gujarati Khandvi','category','Starters','price_per_person',120),
   JSON_OBJECT('id','jain_kofta','name','Jain Malai Kofta','category','Main Course','price_per_person',215),
   JSON_OBJECT('id','undhiyu','name','Surti Undhiyu','category','Main Course','price_per_person',195),
   JSON_OBJECT('id','kesar_pulao','name','Kesar Dry Fruit Pulao','category','Rice','price_per_person',210),
   JSON_OBJECT('id','moong_halwa','name','Moong Dal Halwa','category','Desserts','price_per_person',110)
  ),
  'packages',JSON_ARRAY(
   JSON_OBJECT('id','satvik_basic','name','Satvik Basic','min_price',649,'max_price',749,'duration','6 hours','description','Pure vegetarian satvik menu','inclusions',JSON_ARRAY('Farsan','2 vegetables','Dal and rice','Breads','Sweet')),
   JSON_OBJECT('id','satvik_jain','name','Satvik Jain Special','min_price',849,'max_price',949,'duration','8 hours','description','Strict Jain wedding menu','inclusions',JSON_ARRAY('2 Jain starters','3 Jain mains','Pulao','Breads','2 sweets')),
   JSON_OBJECT('id','satvik_royal','name','Satvik Royal','min_price',1099,'max_price',1249,'duration','10 hours','description','Grand Gujarati and Rajasthani feast','inclusions',JSON_ARRAY('Welcome drinks','3 starters','4 mains','Live farsan','Dessert counter'))
  ),
  'food_extras',JSON_ARRAY(JSON_OBJECT('id','farsan_counter','name','Live Farsan Counter','min_price',80,'max_price',110,'unit','per_plate','icon','fa-solid fa-bowl-food')),
  'media',JSON_OBJECT('images',JSON_ARRAY()),'additional_details',JSON_ARRAY(),'seo',JSON_OBJECT('short_description','Jain and satvik wedding specialist','description','Vendor-created menus and packages.','tags',JSON_ARRAY('catering'),'keywords',JSON_ARRAY('jain catering'))
 ),'active',NOW(),NOW()
),
(
 JSON_OBJECT(
  'schema_version',1,'identity',JSON_OBJECT('name','Deccan Biryani House','category','Catering'),'attributes',JSON_ARRAY(),
  'food_menu_items',JSON_ARRAY(
   JSON_OBJECT('id','chicken_65','name','Chicken 65','category','Starters','price_per_person',260),
   JSON_OBJECT('id','mutton_seekh','name','Mutton Seekh Kebab','category','Starters','price_per_person',340),
   JSON_OBJECT('id','butter_chicken','name','Butter Chicken','category','Main Course','price_per_person',290),
   JSON_OBJECT('id','mutton_rogan','name','Mutton Rogan Josh','category','Main Course','price_per_person',370),
   JSON_OBJECT('id','hyderabadi_biryani','name','Hyderabadi Chicken Biryani','category','Rice','price_per_person',320),
   JSON_OBJECT('id','double_ka_meetha','name','Double Ka Meetha','category','Desserts','price_per_person',95)
  ),
  'packages',JSON_ARRAY(
   JSON_OBJECT('id','deccan_classic','name','Deccan Classic','min_price',899,'max_price',999,'duration','6 hours','description','Classic chicken feast','inclusions',JSON_ARRAY('2 starters','Chicken curry','Chicken biryani','Raita','Dessert')),
   JSON_OBJECT('id','deccan_nawabi','name','Deccan Nawabi','min_price',1199,'max_price',1349,'duration','8 hours','description','Chicken and mutton banquet','inclusions',JSON_ARRAY('3 starters','3 mains','Two biryanis','Breads','2 desserts')),
   JSON_OBJECT('id','deccan_darbar','name','Deccan Darbar','min_price',1499,'max_price',1699,'duration','10 hours','description','Royal Hyderabadi spread','inclusions',JSON_ARRAY('Kebab counter','4 starters','4 mains','Dum biryani station','Dessert lounge'))
  ),
  'food_extras',JSON_ARRAY(JSON_OBJECT('id','kebab_grill','name','Live Kebab Grill','min_price',150,'max_price',210,'unit','per_plate','icon','fa-solid fa-fire-burner')),
  'media',JSON_OBJECT('images',JSON_ARRAY()),'additional_details',JSON_ARRAY(),'seo',JSON_OBJECT('short_description','Hyderabadi and Mughlai catering','description','Vendor-created menus and packages.','tags',JSON_ARRAY('catering'),'keywords',JSON_ARRAY('biryani catering'))
 ),'active',NOW(),NOW()
),
(
 JSON_OBJECT(
  'schema_version',1,'identity',JSON_OBJECT('name','Coastal Feast Catering','category','Catering'),'attributes',JSON_ARRAY(),
  'food_menu_items',JSON_ARRAY(
   JSON_OBJECT('id','fish_fry','name','Surmai Rava Fry','category','Starters','price_per_person',360),
   JSON_OBJECT('id','prawn_koliwada','name','Prawn Koliwada','category','Starters','price_per_person',410),
   JSON_OBJECT('id','goan_fish_curry','name','Goan Fish Curry','category','Main Course','price_per_person',340),
   JSON_OBJECT('id','chicken_sukka','name','Mangalorean Chicken Sukka','category','Main Course','price_per_person',285),
   JSON_OBJECT('id','coconut_rice','name','Coastal Coconut Rice','category','Rice','price_per_person',170),
   JSON_OBJECT('id','bebinca','name','Goan Bebinca','category','Desserts','price_per_person',130)
  ),
  'packages',JSON_ARRAY(
   JSON_OBJECT('id','coastal_breeze','name','Coastal Breeze','min_price',999,'max_price',1149,'duration','6 hours','description','Fish and chicken coastal buffet','inclusions',JSON_ARRAY('2 starters','2 coastal mains','Rice','Breads','Dessert')),
   JSON_OBJECT('id','coastal_grand','name','Coastal Grand','min_price',1299,'max_price',1499,'duration','8 hours','description','Seafood celebration spread','inclusions',JSON_ARRAY('3 seafood starters','3 mains','Coastal rice','Live fry counter','2 desserts')),
   JSON_OBJECT('id','coastal_luxury','name','Coastal Luxury','min_price',1599,'max_price',1849,'duration','10 hours','description','Premium seafood wedding banquet','inclusions',JSON_ARRAY('Welcome drinks','4 starters','4 mains','Live seafood grill','Dessert bar'))
  ),
  'food_extras',JSON_ARRAY(JSON_OBJECT('id','seafood_grill','name','Live Seafood Grill','min_price',180,'max_price',250,'unit','per_plate','icon','fa-solid fa-fire-burner')),
  'media',JSON_OBJECT('images',JSON_ARRAY()),'additional_details',JSON_ARRAY(),'seo',JSON_OBJECT('short_description','Coastal seafood wedding catering','description','Vendor-created menus and packages.','tags',JSON_ARRAY('catering'),'keywords',JSON_ARRAY('seafood catering'))
 ),'active',NOW(),NOW()
);

COMMIT;
