import 'package:sam_app/data/repositories/artigo/artigo_repository.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_viewmodel.dart';

class ArtigosCriadosViewmodel extends ArtigosViewmodel {
  ArtigosCriadosViewmodel(ArtigoRepository repo)
    : super(
        fetchArtigos:
            ({required int page, required Map<String, dynamic>? filters}) =>
                repo.getCriados(page: page),
      );
}
