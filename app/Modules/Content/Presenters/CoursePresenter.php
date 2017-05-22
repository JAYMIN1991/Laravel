<?php

namespace App\Modules\Content\Presenters;

use App\Modules\Content\Transformers\CourseTransformer;
use Flinnt\Repository\Presenter\FractalPresenter;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\SerializerAbstract;

/**
 * Class CoursePresenter
 *
 * @package namespace App\Modules\Content\Presenters;
 */
class CoursePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CourseTransformer();
    }

	/**
	 * Get Serializer
	 * Set ArraySerializer to get single item out of data
	 *
	 * @return SerializerAbstract
	 */
	public function serializer() {
		return new ArraySerializer();
	}
}
