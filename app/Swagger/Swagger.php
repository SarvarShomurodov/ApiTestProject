<?php
namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", format="password")
 * )
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Category model",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="short_description", type="string"),
 *     @OA\Property(property="long_description", type="string"),
 *     @OA\Property(property="image", type="string", format="url", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
  * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="category_id", type="integer", example="1"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="short_description", type="string"),
 *     @OA\Property(property="long_description", type="string"),
 *     @OA\Property(property="image", type="string", format="url", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class Swagger {}
