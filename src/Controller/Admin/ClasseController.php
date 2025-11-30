<?php

namespace App\Controller\Admin;
use App\Entity\Classe;
use App\Form\ClasseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/classes', name: 'admin_classes_')]
class ClasseController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $classes = $em->getRepository(Classe::class)->findBy([], ['nom' => 'ASC']);

        $etudiantsSansClasse = $em->getRepository(Etudiant::class)->findBy(['classe' => null]);
        $allEnseignants = $em->getRepository(Enseignant::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/classe/index.html.twig', [
            'classes'               => $classes,
            'etudiantsSansClasse'   => $etudiantsSansClasse,
            'allEnseignants'        => $allEnseignants,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($classe);
            $em->flush();

            $this->addFlash('success', 'Classe ajoutée avec succès !');

            return $this->redirectToRoute('admin_classes_index');
        }

        return $this->render('admin/classe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classe $classe, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Classe modifiée !');
            return $this->redirectToRoute('admin_classes_index');
        }

        return $this->render('admin/classe/edit.html.twig', [
            'form'   => $form->createView(),
            'classe' => $classe,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Classe $classe, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $classe->getId(), $request->request->get('_token'))) {
            foreach ($classe->getEtudiants() as $etudiant) {
                $etudiant->setClasse(null);
            }

            $em->remove($classe);
            $em->flush();

            $this->addFlash('success', 'Classe supprimée !');
        }

        return $this->redirectToRoute('admin_classes_index');
    }

    #[Route('/{id}/assign-etudiant', name: 'assign_etudiant', methods: ['POST'])]
    public function assignEtudiant(Request $request, Classe $classe, EntityManagerInterface $em): Response
    {
        $etudiant = $em->getRepository(Etudiant::class)->find($request->request->get('etudiant_id'));

        if ($etudiant) {
            $etudiant->setClasse($classe);
            $em->flush();
            $this->addFlash('success', 'Étudiant assigné !');
        }

        return $this->redirectToRoute('admin_classes_index');
    }

    #[Route('/{id}/assign-enseignant', name: 'assign_enseignant', methods: ['POST'])]
    public function assignEnseignant(Request $request, Classe $classe, EntityManagerInterface $em): Response
    {
        $enseignant = $em->getRepository(Enseignant::class)->find($request->request->get('enseignant_id'));

        if ($enseignant && !$classe->getEnseignants()->contains($enseignant)) {
            $classe->addEnseignant($enseignant);
            $em->flush();
            $this->addFlash('success', 'Enseignant assigné !');
        }

        return $this->redirectToRoute('admin_classes_index');
    }

    #[Route('/{classeId}/remove-enseignant/{enseignantId}', name: 'remove_enseignant')]
    public function removeEnseignant(int $classeId, int $enseignantId, EntityManagerInterface $em): Response
    {
        $classe = $em->find(Classe::class, $classeId);
        $enseignant = $em->find(Enseignant::class, $enseignantId);

        if ($classe && $enseignant) {
            $classe->removeEnseignant($enseignant);
            $em->flush();
            $this->addFlash('success', 'Enseignant retiré');
        }

        return $this->redirectToRoute('admin_classes_index');
    }
}
