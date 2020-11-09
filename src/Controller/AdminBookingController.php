<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * Affiche la liste des réservations
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_list")
     * @return Response
     */
    public function index(BookingRepository $repo,Pagination $paginationService,$page)
    {
        $paginationService->setEntityClass(Booking::class)
                          ->setPage($page)
                          // ->setRoute('admin_bookings_list')
        ;

        return $this->render('admin/booking/index.html.twig', [
            'pagination'=>$paginationService
        ]);
    }
    
    /**
     * Edition d'une réservation par l'admin
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function edit(Booking $booking,Request $request,EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(AdminBookingType::class,$booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());

            $booking->setAmount(0);

            $entityManager->persist($booking);
            $entityManager->flush();

            $this->addFlash("success","La réservation a bien été modifiée");
            return $this->redirectToRoute('admin_bookings_list');
        } 

        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * Suppression d'une réservation par l'admin
     * @Route("/admin/booking/{id}/delete",name="admin_booking_delete")
     * @param Booking $booking
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function delete(Booking $booking,EntityManagerInterface $entityManager)
    {
        $entityManager->remove($booking);
        $entityManager->flush();

        $this->addFlash("success","Réservation n° {$booking->getId()} a bien été supprimée !");
        return $this->redirectToRoute('admin_bookings_list');
    }


    
}
