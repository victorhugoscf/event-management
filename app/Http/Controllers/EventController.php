<?php

namespace App\Http\Controllers;

use App\Models\Event;


use Illuminate\Support\Facades\Auth; // Importação correta
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function index()
    {
        $search = request('search');
        if ($search) {
            $events = Event::where('title', 'like', '%' . $search . '%')->get();
        } else {
            $events = Event::all();
        }

        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'private' => 'required|boolean',
            'description' => 'required|string',
            'items' => 'required|array',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = new Event;

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = json_encode($request->items);


        $event->date = $request->date;

        // Lógica para o upload da imagem
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $request->image->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }

        // Verifica se o usuário está autenticado
        if (Auth::check()) { // Usando Auth::check()
            $user = Auth::user(); // Usando Auth::user()
            $event->user_id = $user->id;
            $event->save();

            return redirect()->route('events.index')->with('success', 'Evento criado com sucesso!');
        } else {
            return redirect('/login')->with('error', 'Você precisa estar logado para criar um evento.');
        }
    }

    public function show($id) {
        $event = Event::findOrFail($id); // Isso obtém o evento pelo ID
        return view('events.show', ['event' => $event]);
    }

    public function dashboard()
    {
        $user = auth()->user(); // Obtém o usuário autenticado
        $events = $user->events; // Obtém os eventos que o usuário criou
        $eventsAsParticipant = $user->eventsAsParticipant; // Obtém os eventos que o usuário participa

        return view('events.dashboard', [
        'events' => $events, 
        'eventasparticipant' => $eventsAsParticipant]);
    
    }
    public function destroy($id)
{
    $event = Event::findOrFail($id);

    // Remover todos os participantes do evento antes de deletar o evento
    $event->users()->detach();

    // Agora você pode excluir o evento
    $event->delete();

    return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
}

        
    public function edit($id){
        $user = auth()->user();
        $event= Event::findOrFail($id);
        $event->date = \Carbon\Carbon::parse($event->date);
        if($user->id != $event->user_id){
            return redirect('/dashboard');
            
        }

        return view('events.edit',['event'=>$event]);
    }

    public function update(Request $request){
        $data = $request->all();

         if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }


        Event::findOrFail($request->id)->update($data);
        return redirect('/dashboard')->with('msg', 'Evento alterado com sucesso!');

    }

    public function jointEvent($id)
    {
    $user = auth()->user();
    $event = Event::findOrFail($id);

    // Verifica se o usuário já está participando do evento
    if ($user->eventsAsParticipant()->where('event_id', $id)->exists()) {
        return redirect('/dashboard')->with('msg', 'Você já está participando deste evento.');
    }

    // Se não estiver participando, faz a associação
    $user->eventsAsParticipant()->attach($id);

    return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento!');
    }


    public function leaveEvent($id)
{
    $user = auth()->user();
    $user->eventsAsParticipant()->detach($id); // Remove o evento da lista de participação

    return redirect('/dashboard')->with('msg', 'Você saiu do evento com sucesso.');
}



}