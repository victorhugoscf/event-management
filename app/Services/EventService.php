<?php
namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Validation\ValidationException;



class EventService
{
    public function searchEvents($search)
    {
        return $search ? Event::where('title', 'like', '%' . $search . '%')->get() : Event::all();
    }

   public function createEvent($data)
{
    // Criar um novo evento
    $event = new Event();
    
    // Preencher os dados do evento
    $event->fill($data);

    // Verificar se o usuário está autenticado
    if (Auth::check()) {
        $event->user_id = Auth::id(); // Associar ao usuário autenticado

        // Tentar salvar o evento e verificar o sucesso
        if ($event->save()) {
            return $event; // Retorna o evento criado
        } else {
            throw new \Exception('Erro ao salvar o evento');
        }
    } else {
        throw new \Exception('Usuário não autenticado');
    }
}

 public function validateUserAuthentication()
    {
        if (!Auth::check()) {
            throw new ValidationException('Você precisa estar logado para realizar esta ação.');
        }
    }



    public function validateEventData($data)
    {
        return request()->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'private' => 'required|boolean',
            'description' => 'required|string',
            'items' => 'required|array',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    private function uploadImage($image)
    {
        $extension = $image->extension();
        $imageName = md5($image->getClientOriginalName() . strtotime("now")) . "." . $extension;
        $image->move(public_path('img/events'), $imageName);

        return $imageName;
    }

    public function updateEvent($id, $data)
    {
        $event = Event::findOrFail($id);
        $validatedData = $this->validateEventData($data);

        if (isset($data['image']) && $data['image']->isValid()) {
            $validatedData['image'] = $this->uploadImage($data['image']);
        }

        $event->update($validatedData);
        return $event;
    }

    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->users()->detach(); // Remove todos os participantes
        $event->delete();
    }

      public function getUserEventsAsParticipant(User $user)
    {
        return $user->eventsAsParticipant; // Acesso aos eventos como participante
    }

     public function joinEvent(User $user, $eventId)
    {
        if (!$user->eventsAsParticipant()->where('event_id', $eventId)->exists()) {
            $user->eventsAsParticipant()->attach($eventId);
        }
    }

     public function leaveEvent(User $user, $eventId)
{
    $user->eventsAsParticipant()->detach($eventId);
}
    public function getUserEvents($user)
    {
        return $user->events; // Obtém os eventos que o usuário criou
    }

    public function getEventById($id)
    {
        return Event::findOrFail($id); // Obtém o evento pelo ID
    }

    public function getEvents($search = null)
    {
        return $this->searchEvents($search); // Retorna todos os eventos ou filtrados
    }

    }
   