<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - {{ config('app.name') }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .documentation-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .documentation-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .documentation-content h1 {
            margin-top: 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .documentation-content h2 {
            margin-top: 2rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        .documentation-content h3 {
            margin-top: 1.5rem;
        }
        .documentation-content pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }
        .documentation-content code {
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
        }
        .documentation-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .documentation-content table th,
        .documentation-content table td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }
        .documentation-content table th {
            background-color: #f8f9fa;
        }
        .documentation-content blockquote {
            border-left: 4px solid #eee;
            padding-left: 1rem;
            color: #6c757d;
        }
        .documentation-nav {
            position: sticky;
            top: 1rem;
        }
        .documentation-nav ul {
            list-style: none;
            padding-left: 0;
        }
        .documentation-nav ul ul {
            padding-left: 1rem;
        }
        .documentation-nav a {
            display: block;
            padding: 0.25rem 0;
            color: #495057;
            text-decoration: none;
        }
        .documentation-nav a:hover {
            color: #007bff;
        }
        .documentation-nav .active {
            font-weight: bold;
            color: #007bff;
        }
        @media (max-width: 768px) {
            .documentation-nav {
                position: static;
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="documentation-nav">
                    <h5>Documentation</h5>
                    <ul>
                        <li><a href="{{ route('documentation.timezone') }}" class="{{ request()->routeIs('documentation.timezone') ? 'active' : '' }}">Gestion des fuseaux horaires</a></li>
                        <!-- Autres liens de documentation peuvent être ajoutés ici -->
                    </ul>
                    
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à l'application
                        </a>
                    </div>
                </div>
            </div>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="documentation-container">
                    <div class="documentation-content">
                        {!! $content !!}
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Générer automatiquement la table des matières
        document.addEventListener('DOMContentLoaded', function() {
            const headings = document.querySelectorAll('.documentation-content h2, .documentation-content h3');
            const toc = document.createElement('ul');
            toc.className = 'toc';
            
            let currentList = toc;
            let currentLevel = 2;
            
            headings.forEach(heading => {
                const level = parseInt(heading.tagName.substring(1));
                const id = heading.id || heading.textContent.toLowerCase().replace(/[^\w]+/g, '-');
                heading.id = id;
                
                const item = document.createElement('li');
                const link = document.createElement('a');
                link.href = `#${id}`;
                link.textContent = heading.textContent;
                item.appendChild(link);
                
                if (level > currentLevel) {
                    const subList = document.createElement('ul');
                    currentList.lastChild.appendChild(subList);
                    currentList = subList;
                    currentLevel = level;
                } else if (level < currentLevel) {
                    currentList = toc;
                    currentLevel = level;
                }
                
                currentList.appendChild(item);
            });
            
            // Insérer la table des matières après le premier h1
            const firstH1 = document.querySelector('.documentation-content h1');
            if (firstH1 && toc.children.length > 0) {
                const tocContainer = document.createElement('div');
                tocContainer.className = 'toc-container';
                tocContainer.appendChild(toc);
                firstH1.after(tocContainer);
            }
        });
    </script>
</body>
</html>
