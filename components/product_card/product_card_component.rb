module Components
  module ProductCard
    class ProductCardComponent < Middleman::Extension
      helpers do
        def product_card(opts)
          product = opts[:product]

          concat(
            link_to("/store/#{product.slug}/", class: 'bg-white p-6') do
              image(product) +
              content_tag(:div, class: 'flex') do
                content_tag(:h2, product.title, class: 'flex-grow font-bold mr-3') +
                product_price(product)
              end
            end
          )
        end

        def image(product)
          image_tag(product.images.first.url(fm: :webp, h: 540, w: 540),
                    alt: product.title,
                    class: 'mb-3',
                    loading: 'lazy')
        end
      end
    end
  end
end

::Middleman::Extensions.register(:product_card_component, Components::ProductCard::ProductCardComponent)
