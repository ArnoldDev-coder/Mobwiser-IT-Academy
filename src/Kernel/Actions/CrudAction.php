<?php

namespace Kernel\Actions;

use GuzzleHttp\Psr7\ServerRequest;
use Kernel\Database\NoRecodrException;
use Kernel\Database\Table;
use Kernel\Renderer\Renderer;
use Kernel\Router\Router;
use Kernel\Session\FlashMessage;
use Kernel\Validator;
use Psr\Http\Message\ResponseInterface;


class CrudAction
{
    public string $viewPath;
    public string $routePrefix;
    public array $message = [
        'create' => "L'élément a bien été créé",
        'edit' => "L'élément a bien été modifié",
        'delete' => 'Les informations ont bien été suprimées'
    ];
    private Renderer $renderer;
    private Router $router;
    private $table;
    private FlashMessage $flashMessage;
    use RouterAware;

    public function __construct(
        Renderer $renderer,
        Table $table,
        Router $router,
        FlashMessage $flashMessage
    )
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->table = $table;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @throws NoRecodrException
     */
    public function __invoke(ServerRequest $request): string|ResponseInterface
    {
        $this->renderer->addGlobal('viewPath', $this->viewPath);
        $this->renderer->addGlobal('routePrefix', $this->routePrefix);
        if ($request->getMethod() == "DELETE") {
            return $this->delete($request);
        }
        if (str_ends_with($request->getUri(), "create")) {

            return $this->create($request);
        }
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    public function index(ServerRequest $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->table->findAll()->paginate(12, $params['page'] ?? 1);
        return $this->renderer->render($this->viewPath . '/index', $this->formParams(compact('items')));
    }

    /**
     * @throws NoRecodrException
     */
    private function edit(ServerRequest $request): string|ResponseInterface
    {
        $item = $this->table->find($request->getAttribute('id'));
        $validator = $this->getValidator($request);
        if ($request->getMethod() === "POST") {
            if ($validator->isValid()) {
                $this->table->update($item->id, $this->getParams($request, $item));
                $this->flashMessage->success($this->message['edit']);
                return $this->redirect($this->routePrefix.'.index');
            }
        }
        $errors = $validator->getErrors();
        return $this->renderer->render($this->viewPath . '/edit', $this->formParams(compact('item', 'errors')));
    }



    /**
     * Crée un nouvel élément
     * @param ServerRequest $request
     * @return ResponseInterface|string
     */
    public function create(ServerRequest $request): string|ResponseInterface
    {
        $validator = $this->getValidator($request);
        $item = $request->getParsedBody();
        if ($request->getMethod() === 'POST') {
            if ($validator->isValid()) {
                $this->table->insert($this->getParams($request, $item));
                $this->flashMessage->success($this->message['create']);
                return $this->redirect($this->routePrefix . '.index');
            }
        }
        $errors = $validator->getErrors();
        return $this->renderer->render(
            $this->viewPath . '/create',
            $this->formParams(compact( 'errors'))
        );
    }
    public function delete(ServerRequest $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $this->table->delete($id);
        $this->flashMessage->success($this->message['delete']);
        return $this->redirect($this->routePrefix.'.index');
    }

    public function getParams(ServerRequest $request, mixed $item): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, []);
        }, ARRAY_FILTER_USE_KEY);

    }

    public function getValidator(ServerRequest $request): Validator
    {
        return new Validator(array_merge($request->getParsedBody(), $request->getUploadedFiles()));
    }

    /**
     * ajoute des parametres a envoyer a la vue
     * @param array $params
     * @return array
     */
    public function formParams(array $params):array
    {
        return $params;
    }

}