<?php

namespace App\Http\Controllers;

use App\Media;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use RuntimeException;

class MediaController extends Controller
{

    /**
     * Default Elastic Search document listing
     * @return LengthAwarePaginator
     */
    public function index() {
        return $this->prepareDocs(Media::matchAllSearch());
    }

    /**
     * Full text search for documents
     * @param Request $request
     * @return LengthAwarePaginator|void
     */
    public function search(Request $request) {
        //TODO: Exclude set categories from search.
        $query = $request->has('q')
            ? Str::lower($request->input('q'))
            : abort(404);
        $data = Media::boolSearch()->should('match', ['title' => $query]);

        return $this->prepareDocs($data);
    }

    /**
     * Return document by title
     * @param string $slug
     * @return array|void
     */
    public function title(string $slug) {
        $title = str_replace('-', ' ', $slug);
        $data = Media::boolSearch()
            ->must('match', ['title.alphanumeric' => $title])
            ->execute();

        return $data->total()
            ? $data->documents()->first()->getContent()
            : abort(404);
    }

    /**
     * Get documents by multiple ids
     * TODO: Should return by same order given
     * @param Request $request
     * @return LengthAwarePaginator|void
     */
    public function collect(Request $request) {
        $ids = explode(',',$request->input('ids')) ?? abort(404);
        $data = Media::idsSearch()
            ->values($ids);

        return $this->prepareDocs($data);
    }

    /**
     * Return weekly top documents
     * @param Request $request
     * @return LengthAwarePaginator|null
     */
    public function best(Request $request) {
        $data = Media::rangeSearch()
                ->field('created_at')
                ->gt(now()->subWeek());

        return $this->prepareDocs($data);
    }

    /**
     * Return related documents by Id
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function related(Request $request) {
        $data = Media::rawSearch()
            ->query([
                'more_like_this' => [
                    'fields' => [
                        'title',
                        'categories'
                    ],
                    'like' => [
                        '_id' => $request->input('id')
                    ],
                    'min_term_freq' => 1,
                    'max_query_terms' => 12
                ]
            ]);

        return $this->prepareDocs($data, 12);
    }

    /**
     * Get documents by category
     * @param $slug
     * @return LengthAwarePaginator
     */
    public function category($slug) {
        $category = Str::title($slug);
        $data = Media::boolSearch()
            ->should('match', ['categories' => $category]);

        return $this->prepareDocs($data);
    }

    /**
     * Increment likes column
     * @param $id
     * @return JsonResponse
     */
    public function like($id) {
        if (Media::query()->where('id', $id)->increment('likes')) {
            return response()->json(['success' => true], 200);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Increment dislikes column
     * @param $id
     * @return JsonResponse
     */
    public function dislike($id) {
        if (Media::query()->where('id', $id)->increment('dislikes')) {
            return response()->json(['success' => 1], 200);
        }
        return response()->json(['success' => false], 404);
    }

    /**
     * Prepare only elasticsearch documents with pagination.
     * Note: Pulling documents straight from elasticsearch means no added MySQL query.
     *
     * @param SearchRequestBuilder $documents
     * @param int $perPage
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator|void
     */
    public function prepareDocs(
        SearchRequestBuilder $documents,
        int $perPage = Media::DEFAULT_PAGE_SIZE,
        string $pageName = 'page',
        int $page = null
    ) {
        $page = $page ?? Paginator::resolveCurrentPage($pageName);

        $data = $documents
            ->from(($page - 1) * $perPage)
            ->size($perPage)
            ->execute();

        if (is_null($data->total())) {
            throw new RuntimeException(
                'Search result does not contain the total hits number. ' .
                'Please, make sure that total hits are tracked.'
            );
        }

        if ($data->total() === 0) {
            abort(404);
        }

        $dataArr = [];
        foreach ($data->documents() as $row) {
            $dataArr[] = $row->getContent();
        }

        return new LengthAwarePaginator(
            $dataArr,
            $data->total(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }

}
