<?php

/**
 * canvas class - Generic canvas API controller
 */

namespace Leantime\Domain\Api\Controllers {

    use Illuminate\Support\Str;
    use Leantime\Core\Controller;
    use Leantime\Domain\Projects\Repositories\Projects as ProjectRepository;

    /**
     *
     */
    class Canvas extends Controller
    {
        /**
         * Constant that must be redefined
         */
        protected const CANVAS_NAME = '??';

        private ProjectRepository $projects;

        /**
         * @var \Closure|mixed|object|null
         */
        private mixed $canvasRepo;

        /**
         * constructor - initialize private variables
         *
         * @access public
         * @params parameters or body of the request
         */
        public function init(ProjectRepository $projects)
        {
            $this->projects = $projects;
            $canvasName = Str::studly(static::CANVAS_NAME) . 'canvas';
            $repoName = app()->getNamespace() . "Domain\\$canvasName\\Repositories\\$canvasName";
            $this->canvasRepo = app()->make($repoName);
        }


        /**
         * get - handle get requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function get($params)
        {
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function post($params)
        {
        }

        /**
         * put - handle put requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function patch($params)
        {
            if (
                ! isset($params['id'])
                || ! $this->canvasRepo->patchCanvasItem($params['id'], $params)
            ) {
                return $this->tpl->displayJson(['status' => 'failure'], 500);
            }

            return $this->tpl->displayJson(['status' => 'ok']);
        }

        /**
         * delete - handle delete requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function delete($params)
        {
        }
    }

}
