<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="articles")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getEntityManager()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('article/index.html.twig',array('articles' => $articles));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/article/add",name="article_add")
     */
    public function add(Request $request){
        $article = new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('articles');
        }
        return $this->render('article/add.html.twig',array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @Route("/article/{id}/edit",name="article_edit",requirements={"id"="[0-9]+"})
     * @ParamConverter("article",class="App\Entity\Article")
     * @return Response
     */
    public function edit(Request $request,Article $article){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('articles');
        }
        return $this->render('article/edit.html.twig',array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param Article $article
     * @Route("/article/{id}/delete",name="article_delete")
     * @return Response
     */
    public function delete(Request $request,Article $article){
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('articles');
        }
        return $this->render('article/delete.html.twig',array('form' => $form->createView()));
    }

    private function createDeleteForm(Article $article){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl("article_delete",array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->add('delete',SubmitType::class,array('attr' => ['class' => 'btn btn-danger']))
            ->getForm()
            ;
    }
}
