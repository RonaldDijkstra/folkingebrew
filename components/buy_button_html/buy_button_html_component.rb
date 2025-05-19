module Components
  module BuyButtonHTML
    class BuyButtonHTMLComponent < Middleman::Extension
      helpers do
        def buy_button_html(product, product_image, product_sizes, classes)
          content_tag(:button, 'Add to Cart', class: classes,
                                              data: button_data(product, product_image, product_sizes))
        end

        def button_data(product, product_image, product_sizes)
          if product_sizes
            button_data_base(product, product_image).merge(button_data_sizes(product, product_sizes))
          else
            button_data_base(product, product_image)
          end
        end

        def first_available_size(product)
          # puts product.data.sizes.select(&:size_in_stock).first
          product.data.sizes.select(&:size_in_stock).first.name
        end
      end

      helpers do
        def button_data_base(product, product_image)
          {
            "item-name": product.title,
            "item-id": product.data.product_id,
            "item-quantity": '1',
            "item-price": total_price(product),
            "item-url": "#{base_url}/webshop/#{product.slug}",
            "item-image": image_path("webshop/#{product_image}"),
            "item-has-taxes-included": 'true'
          }
        end

        def button_data_sizes(product, product_sizes)
          {
            "item-custom1-name": 'Sizes',
            "item-custom1-value": first_available_size(product),
            "item-custom1-options": product_sizes
          }
        end
      end
    end
  end
end

::Middleman::Extensions.register(:buy_button_html_component,
                                 Components::BuyButtonHTML::BuyButtonHTMLComponent)
