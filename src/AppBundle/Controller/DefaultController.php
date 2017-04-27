<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Form\UsersSearchForm;
use AppBundle\Repository\UsersRepository;
use AppBundle\Service\FlashMessage;
use AppBundle\Service\Importer\Strategy\ImportException;
use AppBundle\Service\Importer\UsersImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->importAction($request);
    }

    /**
     * @Route("/import", name="import")
     * @param Request $request
     * @return Response
     */
    public function importAction(Request $request)
    {
        /** @var Form $form */
        $form = $this->createFormBuilder()
            ->add('file', FileType::class, ['label' => 'Choose File'])
            ->add('save', SubmitType::class, ['label' => 'Import Users'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var UploadedFile $file */
            $file = $data['file'];

            /** @var UsersImporter $importer */
            $importer = $this->get('import.users');

            try {
                $time = microtime(true);

                $importer->import($file->getPathname());

                $time = microtime(true) - $time;
                $this->addFlash(
                    FlashMessage::TYPE_INfO,
                    sprintf('Import is complete. (%.3f secs spent)', round($time, 3))
                );

            } catch (ImportException $e) {
                $this->addFlash(
                    FlashMessage::TYPE_WARNING,
                    $e->getMessage()
                );
            }

            return $this->redirectToRoute('import');
        }

        return $this->render('default/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->doSearch($request);
        }

        $form = $this->createForm(UsersSearchForm::class);

        return $this->render('default/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function doSearch(Request $request)
    {
        $data = $request->request->all();
        if (!empty($data)) {
            $data = array_filter(reset($data));
        }

        /** @var UsersRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Users');

        $qb = $repo->getSearchQB($data);
        $count = $repo->getSearchResultCount($data);

        $pageSize = 10;
        $pageNum = !empty($data['page']) ? (int)$data['page'] : 0;
        if ($pageNum < 0) {
            $pageNum = 0;
        }
        $pages = (int) floor($count / $pageSize);
        if ($pageNum > $pages) {
            $pageNum = $pages;
        }

        $qb
            ->setMaxResults($pageSize)
            ->setFirstResult($pageNum * $pageSize)
        ;

        $result = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $bufUser = new Users();
        foreach ($result as &$item) {
            $item['age'] = $bufUser->setBirthday($item['birthday'])->getAge();
            $item['birthday'] = $item['birthday']->format('Y-m-d');
        }

        return $this->json(['data' => $result, 'cnt' => $count, 'page' => $pageNum, 'pages' => $pages]);
    }
}
