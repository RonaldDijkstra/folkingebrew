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
          image_tag("webshop/#{product_image}",
                    alt: product.title)
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_thumbnail_component, Components::ProductThumbnail::ProductThumbnailComponent)
