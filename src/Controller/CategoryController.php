<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request,PaginatorInterface $paginator)
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);
        $categoriesQuery = $repo->createQueryBuilder('c')->select('c')->getQuery();
        $categories = $paginator->paginate($categoriesQuery,$request->query->get('page',1),5);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @Route("/categories/add",name="category_add")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request){
        $em=$this->getDoctrine()->getManager();
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/add.html.twig',array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @Route("/category/{id}/edit",name="category_edit")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request,Category $category){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/edit.html.twig',array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @Route("/category/{id}/delete",name="category_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request,Category $category){
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            return $this->redirectToRoute('categories');
        }
        return $this->render('/category/delete.html.twig',array('form' => $form->createView()));
    }

    private function createDeleteForm(Category $category){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete',array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->add('submit',SubmitType::class,array('attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }

    public function count(){
        $categories_count = $this->getDoctrine()->getRepository(Category::class)->getCount();
        $articles_count = $this->getDoctrine()->getRepository(Article::class)->getCount();
        //var_dump($categories_count);die('t');
        return $this->render('includes/menu.html.twig',['category' => $categories_count,'article' => $articles_count]);
    }

}
