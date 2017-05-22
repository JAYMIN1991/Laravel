<?php

namespace App\Modules\Content\Presenters;

use App\Modules\Content\Transformers\CourseContentTransformer;
use Flinnt\Repository\Presenter\FractalPresenter;

/**
 * Class CourseContentPresenter
 *
 * @package namespace App\Modules\Content\Presenters;
 */
class CourseContentPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CourseContentTransformer();
    }
}
