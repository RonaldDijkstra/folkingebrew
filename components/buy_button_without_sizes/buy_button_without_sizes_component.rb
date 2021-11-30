module Components
  module BuyButtonWithoutSizes
    class BuyButtonWithoutSizesComponent < Middleman::Extension
      helpers do
        def buy_button_without_sizes(product, product_image, classes)
          content_tag(:button, 'Add to Cart',
                      class: classes,
                      "data-item-id": product.product_id,
                      "data-item-quantity": '1',
                      "data-item-price": product.price,
                      "data-item-name": product.title,
                      "data-item-max-quantity": product.max_quantity,
                      "data-item-url": "#{base_url}/store/#{product.slug}",
                      "data-item-image": product_image,
                      "data-item-has-taxes-included": 'true')
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_without_sizes_component,
                                 Components::BuyButtonWithoutSizes::BuyButtonWithoutSizesComponent)
