<?php
/*
سطر 65 خلي توكن البوت.
سطر 66 خلي ايدي مالتك.
سطر 67 خلي اسم تخزينك #ملاحظه الاذاعه تدعم نوعين مم تخزين txt او json.
*/
function EditMsg($chat_id,$message_id,$text,$parse_mode,$disable_web_page_preview,$reply_markup){
bot('editMessageText',[
'chat_id'=>$chat_id,
'message_id'=>$message_id,
'text'=>$text,
'parse_mode'=>$parse_mode,
'disable_web_page_preview'=>$disable_web_page_preview,
'reply_markup'=>$reply_markup
]);
}      
function SendMsg($chat_id,$text,$parse_mode,$disable_web_page_preview,$reply_markup,$message_id){ //SendMessage
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>$text,
'parse_mode'=>$parse_mode,
'disable_web_page_preview'=>$disable_web_page_preview,
'reply_markup'=>$reply_markup,
'reply_to_message_id'=>$message_id,
]);
}
function SaveJson($file,$array){  
return file_put_contents($file, json_encode($array));
}
function broadcast($token, $path){
$GetFile = file_get_contents($path);
$ex = explode(".",$path)[1];
if($ex == "json"){
$Users = json_decode($GetFile,true);
}else{
$Users = array_map('intval', explode("\n", $GetFile));
}
$Users = array_filter($Users);
$Users = array_unique($Users);
$Users = json_encode($Users);
$update = json_encode(json_decode(file_get_contents("php://input"),true));    
$url = "https://api.codar.site/Broadcast/$token/?Users=".$Users."&update=".$update;
$res = file_get_contents($url);
return json_decode($res,true);
}
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$chat_id = $message->chat->id;
$text = $message->text;
$chatid = $update->callback_query->message->chat->id;
$messageid = $update->callback_query->message->message_id;
$data = $update->callback_query->data;
$from_id = $message->from->id;
$name = $message->from->first_name;
$Data_id = $update->callback_query->from->id;
$message_id = $message->message_id;
$json = [
'Token'=>'1153754099:AAFKWFpecPbqPfM5BuJUUXyu2Mm_1NQdd6Q', // خلي توكنك بدل هاذ توكن .
'admin'=>'853393439', // خلي ايديك بدل الايدي مالتي.
'file'=>'Member.txt', //ضع اسم تخزينك هنا ان كان صيغ ملفك txt او json.
];

$ex = explode('.',$json['file']);
if($ex[1] == "txt"){
$user_id = explode("\n",file_get_contents($json['file']));
$count = count($user_id)+1;
}elseif($ex[1] == "json"){
$user_id = json_decode(file_get_contents($json['file']),true);
$count = count($user_id['id'])+1; //ضع اسم المكان الذي تنحفظ فيه الايديات بدل كلمه (id) ان كان تخزينك json.
}else{
$count = "عزيزي قم بتأكد من اسم تخزينك 📮.";}
$Key = json_encode(['inline_keyboard' => [ //keyboard 1 #
[['text'=>"📮| ارسال للكل.",'callback_data' =>"b"],['text'=>"📌| عدد الاعضاء.",'callback_data' =>"m"]],
],
]);
$Key_Back = json_encode(['inline_keyboard' => [ //keyboard 2 #
[['text'=>"🔰| رجوع.",'callback_data' =>"Back"]],
],
]);
$Bc = json_decode(file_get_contents("Bc.json"),true);
$caption_bc = '📌| مرحبا بك عزيزي المطور.'.PHP_EOL.'📦| اليك الاوامر الخاصةه بك.'.PHP_EOL.'🎯| اختر من الكيبورد الذي فل الاسفل.';
if($text == "/start" and $from_id == $json['admin']){ //Start 
SendMsg($json['admin'],$caption_bc,"Markdown",True,$Key,$message_id);}
if($message and $text != "/start" and !$data and isset($Bc['Bc']) == "bc" and $from_id == $json['admin']){
if(broadcast($json['Token'], $json['file'])['ok'] == true){
broadcast($json['Token'], $json['file']);
unlink("Bc.json");
}elseif(broadcast($json['Token'], $json['file'])['ok'] == false){
SendMsg($json['admin'],broadcast($json['Token'], $json['file'])['description']."\n"."عذرًا ، لا يمكنك إجراء بث ، حاول الآن بعد ".broadcast($json['Token'], $json['file'])['next_broadcast']." ثانية","markdown",True,$Key_Back,$message_id);
unlink("Bc.json");
}
}
if($data == "m" and $Data_id == $json['admin']){
EditMsg($json['admin'],$messageid,"- *".$count."* 🔰.","Markdown",True,$Key_Back);}
if($data == "b" and $Data_id == $json['admin']){
EditMsg($json['admin'],$messageid,"🎬| الاذاعة تدعم جميع انواع الصيغ."."\n"."📱| حسننا الان ارسل احد انواع الصيغ."."\n"."📹| نص ، متحركةه ، ملف ، صوره ، ملصق ، فيديو ، فيديو نوت ، صوتيات ، اغاني.","Markdown",True,$Key_Back);
$Bc['Bc'] = "bc";SaveJson("Bc.json",$Bc);
}
if($data == "Back" and $Data_id == $json['admin']){
EditMsg($json['admin'],$messageid,"*".str_repeat("-=", 18)."*","Markdown",True,$Key);}
