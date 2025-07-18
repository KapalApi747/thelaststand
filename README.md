Welkom
=======================
Dit is mijn applicatie, THE LAST STAND! Het is een multi-tenant e-commerce platform waarop je je eigen 
webshops kunt openen. Alvast veel plezier met de applicatie!

Opmerking: deze readme gaat ervanuit dat je PHPStorm gebruikt met een WAMPserver.

Door: Yuri Steijaert
---

---

Installatie en configuratie
===========================

```
    1.  Open het project in PHPStorm en start je WAMPserver.
    2.  Open een powershell terminal en navigeer naar de project directory (of open een terminal 
        in PHPStorm). 
        
        Voer het commando 'composer install' uit.
        
    3.  Voer het commando 'npm install' uit.
    4.  Voor het functioneren van de app hebben we een custom symlink nodig.
        Verwijder eerst de volgende directory:
        
            /public/tenancy/assets
    
        Open een nieuwe powershell terminal als admin (!), navigeer naar de project directory 
        (../thelaststand) en voer het volgende commando uit:
        
            New-Item -ItemType Junction -Path .\public\tenancy\assets -Target .\storage\app\tenancy
        
        De benodigde symlink is nu aangemaakt.
        
    5.  Maak een nieuwe .env file aan en kopieer de inhoud van .env.example daarin.
        Voeg je eigen mail server toe (bijv. via mailtrap), en je Stripe API keys + secrets als je deze reeds hebt.
        
    6.  Voer het commando 'php artisan key:generate' uit in de powershell of PHPStorm terminal.
        
    7.  Voer het commando 'php artisan migrate:fresh --seed' uit.
```

Voordat we verder kunnen gaan hebben we eerst 2 nieuwe programma's nodig: Acrylic DNS Proxy, en de Stripe CLI.

---

Acrylic DNS Proxy
========================
Voor het functioneren van de domeinen/subdomeinen in de applicatie heb ik gekozen om het programma
Acrylic DNS Proxy te gebruiken. Hiermee kunnen we nieuwe tenant domeinen zoals 'test1.thelaststand.local' 
toevoegen zonder deze steeds manueel toe te moeten voegen aan het Windows 'hosts' bestand.

---

Stap 1: Acrylic Installatie
--

```
    1.  Surf naar https://mayakron.altervista.org.
    2.  Klik op 'Download Setup for Windows'. Je wordt redirected, de download begint automatisch.
    3.  Start het Acrylic.exe bestand.
    4.  Klik op 'Next'.
    5.  Accepteer de licentie.
    6.  Kies een directory voor de installatie en onthoud deze.
```

---

Stap 2: Windows Network Setup
--

```
In het Windows Network Center moet het een en ander aangepast worden.

    1.  Druk op Windows + S, typ 'Control Panel (Configuratiescherm)' in het zoekveld en druk op Enter.
    2.  Ga naar 'Network and Internet' -> 'Network and Sharing Center'.
    3.  Volg de stappen aangegeven in: 
    
        https://mayakron.altervista.org/support/acrylic/Windows10Configuration.htm
        
        De stappen werken hetzelfde in Windows 11.
```

---

Stap 3: Acrylic Setup
--

```
Nu moeten er nog een aantal zaken aangepast worden in:

        -   AcrylicConfiguration.ini
        -   AcrylicHosts.txt
        
    1.  Ga naar de installatie directory van Acrylic DNS Proxy
        (in mijn geval: 'C:\Program Files (x86)\Acrylic DNS Proxy').
    2.  Open het bestand 'AcrylicUI.exe'.
    3.  Je krijgt een leeg scherm. Klik linksboven op 'File' -> 'Open Acrylic Configuration'.
    4.  Voeg helemaal bovenaan toe, boven [GlobalSection]:
        
            [DNS]
            EnableWildcardSubdomains=true
            WildcardSubdomainPrefix=thelaststand.local
            DNSRecord=thelaststand.local,127.0.0.1
            
    5.  Klik linksboven op 'File' -> 'Save' en wacht op de service restart.
    6.  Klik vervolgens op 'File' -> 'Open Acrylic Hosts'.
    7.  Voeg helemaal onderaan toe:
        
            127.0.0.1 >thelaststand.local
                
        De '>' is belangrijk! Hiermee worden subdomeinen herkend door Acrylic, voor de tenants.
        
    8.  Klik vervolgens op 'File' -> 'Save' en wacht op de service restart.
        Hierna kun je de AcrylicUI sluiten.
```

---

Stap 4: Testen
--

```
Eerst even testen of Acrylic werkt.

    1.  Open een CMD window met admin rechten, en voer het volgende commando in:

            nslookup thelaststand.local
            
        De output moet er als volgt uitzien als alles werkt:
```

![Screenshot Acrylic 1](readme_screenshots/acrylic/screenshot_acrylic_1.png)

Je kunt ook bestaande tenant subdomeinen hiermee testen! Nu weten we dat de domeinen correct functioneren.

Opmerking: mogelijk moet je hierna ook je WAMPserver herstarten.

---

Stripe CLI
=======================
Om orders in de applicatie te kunnen verwerken, heb ik gekozen om de Stripe CLI te gebruiken. Hiermee 
kan ik in een lokaal project Stripe's eigen webhooks implementeren en testen.

---

Stap 1: Installatie
--

```
Nu moet je de Stripe CLI installeren.

    1.  Surf naar https://github.com/stripe/stripe-cli/releases/tag/v1.27.0.
    2.  Download het zip bestand 'stripe_1.27.0_windows_x86_64.zip'.
    3.  Pak het bestand uit naar een map naar keuze (in mijn geval: 'C:\StripeCLI').
```

---

Stap 2: 'stripe.exe' toevoegen aan Windows Path variabelen
--

```
Voeg het bestand 'stripe.exe' toe aan de PATH variabelen van Windows.

    1.  Druk op Windows + S, en type 'Environment Variables' in het zoekveld.
    2.  Klik op 'Edit the system environment variables' (of de Nederlandse vertaling ervan).
    3.  Klik op 'Environment variables'.
    4.  In het onderste gedeelte 'System variables', klik op variabele 'Path'.
        (bovenste gedeelte 'User variables' is ook mogelijk als het een admin account betreft).
    5.  Klik op 'Edit'.
    6.  Klik op 'New'.
    7.  Voer hier het pad naar het uitgepakte 'stripe.exe' bestand in (in mijn geval: 'C:\StripeCLI').
    8.  Klik op 'OK' tot alle vensters gesloten zijn.
```

---

Stap 3: Testen
--

```
Als er nog Powershell of CMD terminals open staan, sluit deze en start Powershell opnieuw op met 
admin rechten.

    1.  We testen of de Stripe CLI werkt. Voer 'stripe --version' in op de powershell terminal en 
        druk op Enter.
    2.  De versie van de Stripe CLI moet verschijnen (bijv.: 'stripe version 1.27.0').
```

---

Stap 4: Stripe CLI Authenticatie
--

```
Van hieruit gaan we ervanuit dat je al een Stripe developer account hebt. Zo niet, maak er eerst eentje 
aan met een nieuwe sandbox!

    1.  Voer 'stripe login' in op de powershell terminal. Druk op Enter.
    2.  Vervolgens krijg je een soortgelijke output in de powershell terminal:
```


![Screenshot Stripe CLI 1](readme_screenshots/stripe/screenshot_stripecli_1.png)

```
    3.  Open de link in de browser en volg de instructies. Gebruik de pairing code die je hebt gekregen.
    4.  Je hebt een Stripe Webhook Secret gekregen. Zet deze erbij in de .env file:

            STRIPE_WEBHOOK_SECRET=whsec_1234567890...
```

Als alles klopt werkt de Stripe CLI nu volledig, en kunnen we deze gebruiken voor lokale projecten!

---

Stap 5: Stripe Webhooks gebruiken
--

```
Om Stripe webhooks te kunnen gebruiken moeten we het volgende doen. Eerst gaan we deze testen:

    1.  Open een powershell terminal als admin en voer het volgende commando uit:

            stripe listen --forward-to http://thelaststand.local:8000/stripe/webhook
            
    2.  Open vervolgens een nieuwe CMD terminal als admin en voer het volgende commando uit:

            stripe trigger checkout.session.completed

    3.  Je krijgt dan, als alles met succes verloopt, een soortgelijke output in de powershell terminal:
```

![Screenshot Stripe CLI 2](readme_screenshots/stripe/screenshot_stripecli_2.png)

Als de webhooks werken is de applicatie helemaal gereed! Hou de Stripe CLI open in de powershell. 

```
    1. Open een PHPStorm terminal (of powershell) en voer het volgende commando uit:
    
            php artisan serve
            
    2. Open daarnaast een nieuwe terminal en voer het volgende commando uit:
    
            npm run dev
```

Je kunt nu naar de homepagina gaan in een browser:

    http://thelaststand.local:8000

Inloggen op het centrale dashboard kan met de volgende gebruikersnaam:

    -   Username: test@example.com
    -   Password: password

Op de knop 'Register' kun je nieuwe tenants toevoegen. De ingevoerde gebruikersnaam en wachtwoord gebruik je dan later 
om in te loggen als Admin van de Tenant.

Na in te loggen kom je op het dashboard terecht van de tenant. Linksboven bij het shop logo vindt je een link naar de 
shop frontend.

Er wordt allerlei dummy data gegenereerd bij het maken van een nieuwe tenant. Deze staan allemaal geregistreerd in het 
livewire component TenantRegistration.

Hopelijk ben je hiermee voldoende geinformeerd. Nogmaals veel plezier bij het gebruiken van de applicatie!
---
