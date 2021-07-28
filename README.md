![prezentare](https://user-images.githubusercontent.com/34480571/127243487-f861ba91-7213-4e34-9242-7f1125de7e44.png)

<h1><b>Tutorial instalare</b></h1>
• Se downloadeaza tot de pe acest git;<br>
• Se pune pe un apache server;<br>
• Se importa unul dintre sql-urile oferite de mine in baza dvs de date : <br>
&nbsp&nbsp&nbsp&nbsp&nbsp=> db_cu_date.sql contine o multitudine de clase si elevi gata introdusi , este o varianta mai buna de testare a platformei;<br>
&nbsp&nbsp&nbsp&nbsp&nbsp=> db_fara_date.sql este o baza de date goala , menita inceputului , nu exista clase sau elevi introdusi , doar un cont de admin care are userul si parola:<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>->Username : admin</b><br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<b>->Parola : info_edu</b><br>
• Se seteaza in cfg/cfg.js domeniul COMPLET , unde a fost pusa platforma;<br>
• Se seteaza in cfg/cfg.php informatiile de la baza de date;<br>
• Gata<br>

<h1><b>Tutorial folosire</b></h1><br>
• Platforma este impartita in doua mari pagini : <br>
&nbsp&nbsp&nbsp&nbsp&nbsp=>Pagina elevi/parinti/profesori;<br>
&nbsp&nbsp&nbsp&nbsp&nbsp=>Pagina admin;<br>
• By default , user-ul o sa fie intampinat de pagina de elevi/parinti/profesori , indiferent de grad-ul acestuia ( elev/prof/admin );<br>
• Un user care are grad-ul de admin regaseste in partea dreapta sus o rotita prin intermediul careia poate intra pe pagina de administratie;<br>
• Pentru a adauga admin unui user , trebuie cautat id-ul acestuia in baza de date in tabelul <b>"lista_conturi"</b> si modificat la sectiunea <b>"grad"</b> din <b>"elev/prof"</b> in <b>"admin"</b>;<br>
• Daca un user are grad-ul de prof , este considerat profesor;<br>
• Daca un user are grad-ul de elev , este considerat elev;<br>
• Daca un user are grad-ul de admin , este considerat admin;<br>

<h1><b>Cerinte sistem</b></h1><br>
• Acces la internet;<br>
• Un browser;<br>
• Un server Apache;<br>
• Conexiune SQL;<br>
<br><br>

<h1><b>Librarii folosite :</b></h1>
• VUE.js<br>
• W3.css<br>
• JQuery<br>
• FontAwesome<br>
• O librarie pentru tooltips<br>
• iziToast<br>
• AOS<br>
• Animate.css<br>

<br><br>
Preview : http://alex-mihai.ro/catalog<br>
