PaperMovies 1.0
===============

Dado o nome de um filme, este código obtém dados e gera um PDF em folha A5 horizontal com os títulos (pt e en) do filme, sinópse, géneros, ano, classificação IMDB e poster. A ideia é imprimir o PDF gerado para os filmes que se tenha e guardá-los ao pé da TV para quando se quiser escolher um filme para ver. Acede ao IMDB e ao cinema.sapo.pt para obtenção dos dados.

A UI é demasiado básica, não dediquei tempo a fazer uma coisa "pipi" mas pode ser que um dia o faça.

Requisitos:
-----------

O código é PHP, por isso é necessário um servidor de PHP com permissão de escrita na pasta onde está o código.

Utilização:
-------------

A página inicial mostra dois campos de entrada.
Para a maioria dos casos, o primeiro campo, o título original do filme, é suficiente. O nome não tem que ser 100% correcto pois será efectuada uma pesquisa no Google que procura a página no IMDB do filme. Por exemplo, pode ser procurada a palavra "shawshank" que o filme "The Shawshank Redemption" será encontrado.

No entanto há filmes que têm um ID no Sapo Cinema ligeiramente diferente do esperado e esses terão que ser procurados à mão. Por exemplo, tentar procurar por "godfather" não vai resultar. O código retornará um erro e com um link para a pesquisa no Sapo Cinema e lá devem encontrar o filme que procuram. Copiem o URL do filme e coloquem no campo Sapo ID/URL.
Desta forma estão a garantir que serão usados os dados daquela página do Sapo Cinema que especificaram.

Autor:
------

Carlos Fonseca
carlosefonseca at gmail

Código @ [GitHub](http://github.com/carlosefonseca/PaperMovies)


Demo: [carlosefonseca.com/papermovies](http://carlosefonseca.com/papermovies)


Thanks:
-------

*Ao Sapo pela API Sapo Cinema - http://cinema.sapo.pt    
*To FPDF's author - http://www.fpdf.org
*To IMDB for existing and for making people scrape their site...