<?php

namespace App\Controller;

use App\Entity\Bouteille;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManagerInterface; 

class ScraperController extends AbstractController
{
    /**
     * @Route("/scrape", name="scrape")
     */
    public function scrapeData(EntityManagerInterface $entityManager): Response 
    {
        set_time_limit(0);
        $client = HttpClient::create();

        $pageMax = 2; 
        for ($page = 1; $page <= $pageMax; $page++) {
            $url = "https://www.saq.com/fr/produits/vin?p={$page}&product_list_limit=24&product_list_order=name_asc";
            $response = $client->request('GET', $url);
            $html = $response->getContent();
            $crawler = new Crawler($html);

            // Scraper les informations pour chaque élément de produit
            $crawler->filter('li.product-item')->each(function (Crawler $node) use ($client, $entityManager) { 
                $nom = $node->filter('.product.name.product-item-name')->text();
                $code = $node->filter('.saq-code span:last-child')->text();
                $lienProduit =  $node->filter('a.product.photo.product-item-photo')->attr('href');
                $srcImage =  $node->filter('img.product-image-photo')->attr('src');
                $srcsetImage =  $node->filter('img.product-image-photo')->attr('srcset');
                $prixText = $node->filter('.price')->text();
                $prix = (float) preg_replace('/[^0-9,.]/', '', $prixText);
                $prix = number_format($prix, 2, '.', '');
                $identitiy =  $node->filter('.product.product-item-identity-format')->text();
                $identitiyArray = explode('|', $identitiy);
                $type = trim($identitiyArray[0]);
                $format = trim($identitiyArray[1]);

                // Récupérer le millesime du vin
                $millesime = null;
                preg_match('/\b\d{4}\b/', $nom, $matches);
                if (!empty($matches)) {
                    $millesime = $matches[0];
                }

                // Scraper les détails supplémentaires à partir de la page produit
                $detailResponse = $client->request('GET', $lienProduit);
                if ($detailResponse->getStatusCode() === 200) {
                    $detailHtml = $detailResponse->getContent();
                    $detailCrawler = new Crawler($detailHtml);
                    $informations = [];

                    $detailCrawler->filter('.list-attributs li')->each(function (Crawler $li) use (&$informations) {
                        $span = trim($li->filter('span')->text());
                        $strong = trim($li->filter('strong')->text());
                        $informations[$span] = $strong;
                    });

                    $pays = $informations['Pays'] ?? null;
                    $region = $informations['Région'] ?? null;
                    $cepage = $informations['Cépage'] ?? null;
                    $designation = $informations['Désignation réglementée'] ?? 'non';
                    $degre = $informations['Degré d\'alcool'] ?? null;
                    $tauxSucre = $informations['Taux de sucre'] ?? null;
                    $couleur = $informations['Couleur'] ?? null;
                    $producteur = $informations['Producteur'] ?? null;
                    $agentPromotion = $informations['Agent promotionnel'] ?? null;
                    $produitQuebec = $informations['Produit du Québec'] ?? null;
                    $pastilleGoutTitre = $detailCrawler->filter('.wrapper-taste-tag img')->count() > 0
                        ? str_replace('Pastille de goût :', '', $detailCrawler->filter('.wrapper-taste-tag img')->attr('title'))
                        : null;
                    $pastilleImageSrc = $detailCrawler->filter('.wrapper-taste-tag img')->count() > 0
                        ? $detailCrawler->filter('.wrapper-taste-tag img')->attr('src')
                        : null;

                    // Mettre à jour ou créer une nouvelle bouteille
                    $bouteille = $entityManager->getRepository(Bouteille::class)->find($code);

                    if (!$bouteille) {
                        $bouteille = new Bouteille();
                        $bouteille->setCode($code); // Assurez-vous que la propriété $code est définie dans l'entité Bouteille
                    }

                    $bouteille->setNom($nom);
                    $bouteille->setPrix($prix);
                    $bouteille->setPays($pays);
                    $bouteille->setFormat($format);
                    $bouteille->setType($type);
                    $bouteille->setLienProduit($lienProduit);
                    $bouteille->setSrcImage($srcImage);
                    $bouteille->setSrcsetImage($srcsetImage);
                    $bouteille->setDesignation($designation);
                    $bouteille->setDegre($degre);
                    $bouteille->setTauxSucre($tauxSucre);
                    $bouteille->setRegion($region);
                    $bouteille->setCepage($cepage);
                    $bouteille->setCouleur($couleur);
                    $bouteille->setMillesime($millesime);
                    $bouteille->setProducteur($producteur);
                    $bouteille->setAgentPromotion($agentPromotion);
                    $bouteille->setProduitQuebec($produitQuebec);
                    $bouteille->setPastilleGoutTitre($pastilleGoutTitre);
                    $bouteille->setPastilleImageSrc($pastilleImageSrc);

                    $entityManager->persist($bouteille);
                    $entityManager->flush();

                    echo $nom . ' scraped successfully.<br>';
                } else {
                    echo 'Failed to retrieve product details for ' . $nom . '<br>';
                }
            });
        }

        return new Response('Scraping process completed.');
    }
}
