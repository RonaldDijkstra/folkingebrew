module Components
  module ProductThumbnail
    class ProductThumbnailComponent < Middleman::Extension
      helpers do
        def product_thumbnail(opts = nil)
          product = opts[:product]
          product_image = opts[:product_image]
          classes = 'product-thumbnail w-16 h-16 mr-1 ml-1 rounded-lg overflow-hidden shadow
                     md:w-20 md:h-20 lg:h-24 lg:w-24'
          if opts[:index].zero?
            return content_tag(:button, thumbnail(product_image, product),
                               class: "#{classes} thumbnail-active")
          end
          content_tag(:button, thumbnail(product_image, product), class: classes)
        end

        def thumbnail(product_image, product)
          content_tag(:picture, thumbnail_img(product_image, product))
        end

        def thumbnail_img(product_image, product)
          image_tag(product_image.url,
                    alt: product.title,
                    srcset: "#{product_image.url}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=1 1x,
                             #{product_image.url}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=2 2x,
                             #{product_image.url}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=3 3x")
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_thumbnail_component, Components::ProductThumbnail::ProductThumbnailComponent)
