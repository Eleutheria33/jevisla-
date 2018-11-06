<?php

namespace Jevisla\ForumsBundle\Controller;

use Discutea\DForumBundle\Controller\Base\BaseController;
use Discutea\DForumBundle\Entity\Category;
use Discutea\DForumBundle\Entity\Forum;
use Discutea\DForumBundle\Entity\Post;
use Discutea\DForumBundle\Entity\Topic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @author David Verdier <contact@discutea.com>
 *
 * Class AdminController
 *
 * METHODS LIST:
 * indexAction() return discutea_forum_admin_dashboard
 * newCategoryAction(Request $request) Create a new category
 * editCategoryAction(Request $request, $id) Edit a category
 * removeCategoryAction(Request $request, $id) Drop a category
 * newForumAction(Request $request, $cid) Create a new forum
 * editForumAction(Request $request, $id) Edit a forum
 * removeForumAction(Request $request, $id) Drop a forum
 * getRolesList() Return list of symfony roles
 */
class RecentsController extends BaseController
{
    // @Security("is_granted('ROLE_MODERATOR')") à ajouter sous la route pour augmenter la sécurité

    /**
     * @Route("/recent", name="discutea_forum_accueil_dashboard")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recentsPostsAction()
    {
        $em = $this->getEm();
        $posts = $em->getRepository(Post::class)->findBy(array(), array('date' => 'desc'));
        $forums = $this->retrieveForums($em);
        $categories = $this->retrieveCategories($em);

        return $this->render(
            '@DForum/index.recentsPosts.html.twig',
            array(
            'posts' => $posts,
            'forums' => $forums,
            'categories' => $categories,
            )
        );
    }

    public function recentsTopicsAction()
    {
        $em = $this->getEm();
        $topics = $em->getRepository(Topic::class)->findBy(array(), array('date' => 'desc'));
        $forums = $this->retrieveForums($em);
        $categories = $this->retrieveCategories($em);

        return $this->render(
            '@DForum/index.recentsTopics.html.twig',
            array(
            'topics' => $topics,
            'forums' => $forums,
            'categories' => $categories,
            )
        );
    }

    public function retrieveForums($em)
    {
        if ($this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            $forums = $em->getRepository(Forum::class)->findAll();
        } else {
            $forums = null;
        }

        return  $forums;
    }

    public function retrieveCategories($em)
    {
        if ($this->getAuthorization()->isGranted('ROLE_ADMIN')) {
            $categories = $em->getRepository(Category::class)->findBy(array(), array('position' => 'desc'));
        } else {
            $categories = null;
        }

        return  $categories;
    }
}
