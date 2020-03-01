<?php
$MESS ['DX_BF_MODULE_NAME'] = "Developx: форма обратной связи";
$MESS ['DX_BF_MODULE_DESCRIPTION'] = "Форма на инфоблоках с гугл каптчей";
$MESS ['DX_BF_EVENT_NAME'] = "Новая завявка с формы обратной связи";
$MESS ['DX_BF_EVENT_DESCRIPTION'] = "#ID# - ID заявки
#USER_ID# - ID пользователя
#FORM_DATA# - Данные формы
#IBLOCK_ID# - ID Инфоблока
#IBLOCK_TYPE# - Тип инфоблока";
$MESS ['DX_BF_EVENT_SUBJECT'] = "На сайте #SITE_NAME# была оставлена новая заявка - #ID#";
$MESS ['DX_BF_EVENT_MESSAGE'] = "На сайте #SITE_NAME# была оставлена новая заявка<br>
<br>
ID пользователя: #USER_ID#<br><br>
Данные формы:<br>
#FORM_DATA#
<br>
<a href='#SERVER_NAME#/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&ID=#ID#&lang=ru&find_section_section=0&WF=Y'>Ссылка на заявку</a>";