module Components
  module BuyButtonWithSizes
    class BuyButtonWithSizesComponent < Middleman::Extension
      helpers do
        def buy_button_with_sizes(product, product_image, product_sizes, classes)
          content_tag(:button, 'Add to Cart',
                      class: classes, "data-item-id": product.product_id,
                      "data-item-quantity": '1', "data-item-price": product.price,
                      "data-item-name": product.title, "data-item-max-quantity": product.max_quantity,
                      "data-item-custom1-name": 'Sizes', "data-item-custom1-value": first_available_size(product),
                      "data-item-custom1-options": product_sizes, "data-item-url": "#{base_url}/store/#{product.slug}",
                      "data-item-image": product_image, "data-item-has-taxes-included": 'true')
        end

        def first_available_size(product)
          product.sizes.select(&:size_in_stock).first.size
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_with_sizes_component,
                                 Components::BuyButtonWithSizes::BuyButtonWithSizesComponent)
