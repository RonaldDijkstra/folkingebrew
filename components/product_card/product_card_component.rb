module Components
  module ProductCard
    class ProductCardComponent < Middleman::Extension
      helpers do
        def product_card(opts)
          product = opts[:product]

          concat(
            link_to("/webshop/#{product.slug}/", class: 'bg-white p-6') do
              content_tag(:figure, class: 'relative') do
                product_discount(product, 'absolute right-3 top-3') +
                image(product)
              end +
              content_tag(:div, class: 'flex') do
                content_tag(:h2, product.title, class: 'flex-grow font-bold mr-2') +
                content_tag(:div, product.data.price ? product_price(product) : "", class: 'flex flex-col items-end')
              end
            end
          )
        end

        private

        def image(product)
          image_url = product.data.images.first

          image_tag("webshop/#{image_url}",
                    alt: product.title,
                    class: 'mb-3',
                    height: '540',
                    width: '540',
                    loading: 'lazy')
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_card_component, Components::ProductCard::ProductCardComponent)
