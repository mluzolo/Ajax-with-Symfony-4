# Ajax with Symfony 4 

 
## Introduction

In the following example, I will show you how to use Ajax under Symfony 4.
This tutorial is dedicated to all the people who like me had trouble finding information through the Symfony Documentation.

The principle is simple, we will add nicknames in an entity of the same name. And we will display what has been entered in the database via a table that is built using AJAX.


# Requirements

- PHP 7.1.3 or higher;
- and the usual Symfony application requirements.

## Sommaire  
- [A.1. Creating Symfony project](#a1-projet)
- [A.2. Creating entity](#a2-entite)
- [A.3. Creating controller](#a3-repository)
- [A.4. Creating form](#a4-js)
- [A.5. Creatinfg repository](#a5-repository)
- [A.6. We give all the instructions to the controller](#a6-controller)
- [A.7.  JS Code](#a7-js)
 

# A.1. Creating Symfony project
```sh
composer create-project symfony/website-skeleton tpAjax
php bin/console server:run
php bin/console doctrine:database:create (tjAjax)
php bin/console make:controller (HomeController)
```


# A.2. Creating entity
```sh
php bin/console make:entity (Pseudo)
Can this field be null in the database (nullable) (yes/no) [
>yes]:
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
# A.3. Creating controller
```sh

php bin/console make:controller (HomeController)

```
# A.4. Creating form

```sh

php bin/console make:form  (PseudoFormType)

```

In file PseudoFormType.php
```bash


class PseudoFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('pseudo', TextType::class)
                ->add('submit', SubmitType::class, [
                    'label' => 'send'
                ])
        ;
    }
```


# A.5. Creatinfg repository

Dans le fichier PseudoRepository : 

```bash

   public function findByPseudo()
    {
        return $this->createQueryBuilder('p')
            ->select('p.pseudo') 
            ->getQuery()
            ->getResult()
        ;
    }

```


# A.6. We give all the instructions to the controller

```bash

public function index()
    {
         // Creating a nickname    
        $nouveauPseudo = new Pseudo();
         
        //créating a form
        $form = $this->createForm(PostFormType::class, $nouveauPseudo)
                ->handleRequest($request);

        //If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            // Backup to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($nouveauPseudo);
            $em->flush();
        }
```

AJAX processing
```bash
public function ajaxAction() {

$listPseudos = $this->getDoctrine()->getRepository(\App\Entity\Pseudo::class)->findByPseudo();

$myJsonResponse = new JsonResponse($listPseudos);
       
return $myJsonResponse;
    }
```


# A.7.  JS Code

```bash

//form reset 
$("input:text").val(""); // empty the input field after validation of the form
// Tip: the $pseudo property must accept a null value

```


```bash
$.ajax({

                url: '{{ path('ajax') }}',
                type: 'POST',
                dataType: 'json',

                async: true,

                success: function (data) {

                    console.log(data);
                    for (i = 0; i < data.length; i++)
                    {
                        $('.Pseudo').append('<tr><td>' + data[i].pseudo + '</td></tr>'); // Filling in the lines of the table

                        

                    }
                }


            });




        });

```

# Result

![Capture](https://github.com/mluzolo/Ajax-with-Symfony-4/blob/master/Capture%20d%E2%80%99e%CC%81cran%202019-10-11%20a%CC%80%2014.16.59.png?raw=true)
