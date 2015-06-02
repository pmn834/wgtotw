DELETE FROM mvc_tag2question;
DELETE FROM mvc_comments;
DELETE FROM mvc_tag;
 
INSERT INTO mvc_comments (commentTypeId, questionId, parentId, userId, userAcronym, userEmail, title, text, created) VALUES
  (1, null, null, 3, 'doe2', 'doe2@wgtotw.nu', 'Enkel bildredigering', 'Jag letar efter ett program som kan användas för att göra enkla redigeringar av bilder. Filformaten som jag använder är främst JPEG och PNG. Jag behöver kunna beskära bilder, ändra storlek, justera ljusstyrka/kontrast , och konvertera mellan olika filformat. Det är ett plus om programmet är enkelt att lära sig.', NOW()),
  (2, 1, null, 5, 'doe4', 'doe4@wgtotw.nu', 'Enkel bildredigering', 'GIMP finns i en portabel version, som fungerar perfekt för mig. Finns att hämta här, [länk](http://portableapps.com/apps/graphics_pictures/gimp_portable).', NOW()),
  (2, 1, null, 2, 'doe', 'doe@wgtotw.nu', 'Enkel bildredigering', 'Jag brukar använda XnView när det handlar om dessa typer av bildredigeringar. Programmet är främst tänkt som en bildbrowser, men kan också användas för enklare redigering. Länk: [XnView](http://www.xnview.com/en/index.php).', NOW()),
  (3, 1, 2, 4, 'doe3', 'doe3@wgtotw.nu', 'Enkel bildredigering', 'Jag gillar också GIMP, men programmet är kanske lite för avancerat för dessa uppgifter?', NOW()),
  (1, null, null, 2, 'doe', 'doe@wgtotw.nu', 'HTML/CSS-editor', 'Vilket är det bästa alternativet om man vill skriva HTML och CSS? Vill gärna ha förslag på program som har syntax-highlight för dessa, om PHP stöds är också bra. Har testat några olika program, men inte fastnat riktigt för någon av dessa ännu...', NOW()),
  (2, 5, null, 3, 'doe2', 'doe2@wgtotw.nu', 'HTML/CSS-editor', 'Jag använder för det mesta Notepad++. Det funkar bra för mig och programmet stöder ett stort antal programmerings-/märkspråk. Kan laddas ner här: [Notepad++](https://notepad-plus-plus.org/).', NOW()),
  (2, 5, null, 1, 'admin', 'admin@wgtotw.nu', 'HTML/CSS-editor', 'Min favorit är *Geany*. Programmet kan anpassas för att passa de egna behoven och kan även fungera som ett slags mini-IDE med kompilering etc. [Geany](http://portableapps.com/apps/development/geany_portable)', NOW()),
  (2, 5, null, 4, 'doe3', 'doe3@wgtotw.nu', 'HTML/CSS-editor', 'Jag gillar PSPad! Har använt detta program länge och det har alltid funkat bra för mig. PSPad har också en inbyggd FTP-klient så att filer kan laddas upp till en server direkt i texteditorn! Länk: [PSPad](http://www.pspad.com/).', NOW()),
  (3, 5, 7, 5, 'doe4', 'doe4@wgtotw.nu', 'HTML/CSS-editor', 'Jag använder också Geany. Funkar mycket bra!', NOW()),
  (1, null, null, 5, 'doe4', 'doe4@wgtotw.nu', 'Alternativ till WinZip', 'Jag leter efter ett alternativ till WinZip som kan användas på en dator utan att man behöver installera programmet. Behöver kunna skapa och extrahera filer i zip-format. Vet inte riktigt var jag ska börja, kan nån hjälpa mig med detta?', NOW()),
  (2, 10, null, 4, 'doe3', 'doe3@wgtotw.nu', 'Alternativ till WinZip', 'PeaZip borde passa in på denna beskrivning. Förutom zip-filer kan man spara och öppna i många andra format också. Finns att läsa mer om och ladda ner [här](http://peazip.sourceforge.net/).', NOW()),
  (2, 10, null, 1, 'admin', 'admin@wgtotw.nu', 'Alternativ till WinZip', 'Du borde definitivt kolla upp 7-Zip. Har allt man behöver och 7z-formatet kan komprimera filer mycket effektivt. Portabel version: [7-Zip](http://portableapps.com/apps/utilities/7-zip_portable).', NOW()),
  (1, null, null, 4, 'doe3', 'doe3@wgtotw.nu', 'Program för vektorgrafik', 'Det verkar finnas många program för att skapa och redigera rastergrafik, men inte så många som hanterar vektorgrafik... Har inte jobbat så mycket med vektorgrafik - vilka program bör man testa? ', NOW()),
  (2, 13, null, 2, 'doe', 'doe@wgtotw.nu', 'Program för vektorgrafik', 'Ett program jag brukar använda är Inkscape. Med detta program kan man få riktigt bra resultat, och om man vill även exportera till rastergrafik - t.ex. för visning på webben. Länk: [Inkscape](https://inkscape.org/en/).', NOW()),
  (2, 13, null, 3, 'doe2', 'doe2@wgtotw.nu', 'Program för vektorgrafik', 'För enklare vektorgrafik som diagram kan jag rekommendera Dia. Innehåller ett stort antal färdiga former som man kan använda för att bygga upp sin bild. [Dia](http://portableapps.com/apps/office/dia_portable)', NOW()),
  (3, 13, 14, 1, 'admin', 'admin@wgtotw.nu', 'Program för vektorgrafik', 'Håller med dig om Inkscape, ett av mina favoritprogram! Med kombinationen Inkscape och GIMP kan man göra det mesta.', NOW()),
  (1, null, null, 5, 'doe4', 'doe4@wgtotw.nu', 'Ordbehandlare sökes', 'Jag letar efter ett gratisprogram för ordbehandling. Det behöver inte ha så många avancerade funktioner, men helst ha de vanligaste funktionerna som rubriker, listor och tabeller. Någon som har några tips?', NOW()),
  (2, 17, null, 2, 'doe', 'doe@wgtotw.nu', 'Ordbehandlare sökes', 'Mitt förslag är att du testar LibreOffice. Det är ett helt Office-paket, så förutom ordbehandling så har man tillgång till kalkylblad, presentationer m.m. Jag brukar använda mig av detta program för det mesta. Programmet finns att hämta [här](http://portableapps.com/apps/office/libreoffice_portable).', NOW()),
  (2, 17, null, 2, 'doe3', 'doe3@wgtotw.nu', 'Ordbehandlare sökes', 'Ett annat alternativ som kan vara värt att testa är AbiWord. Jag har inte använt detta program så mycket själv, men det verkar ha de funktion som du efterfrågar. Länk: [AbiWord](http://portableapps.com/apps/office/abiword_portable).', NOW())
;

INSERT INTO mvc_tag (name) VALUES
  ('grafik'), ('bildredigering'), ('textredigering'), ('html'), ('css'), ('zip'), ('arkivfil'), ('komprimering'), ('vektorgrafik'), ('ordbehandling')
;

INSERT INTO mvc_tag2question (idQuestion, idTag) VALUES
  (1, 1),
  (1, 2),
  (5, 3),
  (5, 4),
  (5, 5),
  (10, 6),
  (10, 7),
  (10, 8),
  (13, 1),
  (13, 9),
  (17, 3),
  (17, 10)
;
