<?php


namespace App\Http\Controllers;

use App\Services\EventService;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use App\Services\ImageUploadService;


class EventController extends Controller
{
    protected $eventService;
    protected $imageUploadService;

    public function __construct(EventService $eventService, ImageUploadService $imageUploadService)
    {
         $this->eventService = $eventService;
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {
        $search = request('search');
        $events = $this->eventService->getEvents($search);

        return view('welcome', ['events' => $events, 'search' => $search]);
    }

     public function create()
    {
        $this->eventService->validateUserAuthentication(); // Valida a autenticação do usuário
        return view('events.create');
    }

 public function store(Request $request)
    {
        $this->eventService->validateUserAuthentication(); // Valida a autenticação do usuário
        $validatedData = $this->eventService->validateEventData($request->all()); // Valida os dados do evento

        // Tente fazer o upload da imagem
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validatedData['image'] = $this->imageUploadService->upload($request->file('image'));
        }

        // Chama o método do serviço para criar o evento
        $event = $this->eventService->createEvent($validatedData);

        return redirect()->route('events.index')->with('success', 'Evento criado com sucesso!');
    }






    public function show($id)
    {
        $event = $this->eventService->getEventById($id);
        return view('events.show', ['event' => $event]);
    }

    public function dashboard()
{
    $user = auth()->user(); // Obtém o usuário autenticado
    $events = $this->eventService->getUserEvents($user); // Obtenha os eventos que o usuário criou
    $eventsAsParticipant = $this->eventService->getUserEventsAsParticipant($user); // Obtenha os eventos que o usuário participa

    return view('events.dashboard', [
        'events' => $events, 
        'eventasparticipant' => $eventsAsParticipant
    ]);
}


    public function destroy($id)
    {
        $this->eventService->deleteEvent($id);
        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $event = $this->eventService->getEventById($id);

        if ($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request, $id) // 
{
    $this->eventService->updateEvent($id, $request->all()); 
    return redirect('/dashboard')->with('msg', 'Evento alterado com sucesso!');
}


    public function joinEvent($id)
{
    // Verifica se o usuário está autenticado
    if (!auth()->check()) {
        // Redireciona para a página de login se não estiver autenticado
        return redirect()->route('login')->with('msg', 'Você precisa estar logado para participar de um evento.');
    }

    $user = auth()->user();
    $event = $this->eventService->getEventById($id); // Obtenha o evento pelo ID

    // Verifica se o usuário já está participando do evento
    if ($user->eventsAsParticipant()->where('event_id', $id)->exists()) {
        return redirect('/dashboard')->with('msg', 'Você já está participando deste evento.');
    }

    // Se não estiver participando, faz a associação
    $this->eventService->joinEvent($user, $id); // Chame o método do EventService para juntar-se ao evento

    return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento!');
}



    public function leaveEvent($id)
    {
        $user = auth()->user();
          
    if (!$user) {
        return redirect()->route('login')->with('error', 'Você precisa estar logado para sair do evento.');
    }

    // Chama o método leaveEvent no EventService com os parâmetros corretos
    $this->eventService->leaveEvent($user, $id);
    
    return redirect('/dashboard')->with('msg', 'Você saiu do evento com sucesso.');
}
    

    
}