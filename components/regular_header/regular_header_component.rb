module Components
  module RegularHeader
    class RegularHeaderComponent < Middleman::Extension
      helpers do 
        def regular_header(opts)
          content_tag(:header, class: "max-w-8xl text-center px-4 py-12 normal-page-header") do 
            content_tag(:h1, opts[:title], class: "text-white text-4xl")
          end 
        end
      end
    end
  end
end

::Middleman::Extensions.register(:regular_header_component, Components::RegularHeader::RegularHeaderComponent)
