import 'package:flutter/material.dart';
import 'dart:typed_data';

import 'package:sam_app/data/cache/image_cache_service.dart';
import 'package:sam_app/data/enums/tipo_autor_publicacao.dart';
import 'package:sam_app/data/repositories/publicacao/publicacao_repository.dart';
import 'package:sam_app/data/services/publicacao/publicacao_service.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_images_page.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_page.dart';
import 'package:sam_app/presentation/widgets/cached/cached_avatar.dart';
import 'package:sam_app/presentation/widgets/cached/cached_post_image.dart';
import 'package:sam_app/presentation/widgets/cached/cached_post_image_grid.dart';
import 'package:sam_app/shared/constants.dart';

class FeedPostCard extends StatefulWidget {
  final int idPublicacao;
  final int? idGrupoEstudo;
  final String name;
  final String cursoInfo;
  final String content;
  final int comments;
  final int likes;
  final bool liked;
  final List<String> imageHashes;
  final String? avatarHash;
  final Color avatarColor;
  final int idAutor;
  final TipoAutorPublicacao tipoAutorPublicacao;
  final bool openDetails;

  final PublicacaoRepository publicacaoRepository = PublicacaoRepository(
    PublicacaoService(),
  );

  FeedPostCard({
    super.key,
    required this.idPublicacao,
    required this.idGrupoEstudo,
    required this.name,
    required this.cursoInfo,
    required this.content,
    required this.comments,
    required this.likes,
    required this.imageHashes,
    required this.avatarColor,
    required this.idAutor,
    required this.tipoAutorPublicacao,
    this.liked = false,
    this.openDetails = true,
    this.avatarHash,
  });

  String imageUrlFromHash(String hash) => '$baseUrl/file/image/$hash';

  @override
  State<FeedPostCard> createState() => _FeedPostCardState();
}

class _FeedPostCardState extends State<FeedPostCard> {
  final ImageCacheService _imageCacheService = ImageCacheService();

  void _openPostImages() {
    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => PostImagesPage(
          name: widget.name,
          cursoInfo: widget.cursoInfo,
          avatarBytes: widget.avatarHash != null
              ? _imageCacheService.getCachedImageBytes(
                  widget.imageUrlFromHash(widget.avatarHash!),
                )
              : null,
          imagesBytes: widget.imageHashes
              .map(
                (hash) => _imageCacheService.getCachedImageBytes(
                  widget.imageUrlFromHash(hash),
                ),
              )
              .whereType<Uint8List>()
              .toList(),
          comments: widget.comments,
          likes: widget.likes,
        ),
      ),
    );
  }

  void _openPostPage() {
    if (!widget.openDetails) return;

    Navigator.of(context).push(
      MaterialPageRoute(
        builder: (_) => PostPage(
          idPublicacao: widget.idPublicacao,
          idGrupoEstudo: widget.idGrupoEstudo,
          idAutor: widget.idAutor,
          tipoAutorPublicacao: widget.tipoAutorPublicacao,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final imageCount = widget.imageHashes.length.clamp(1, 4);
    final images = widget.imageHashes.take(imageCount).toList();

    return GestureDetector(
      onTap: _openPostPage,
      child: Card(
        color: Theme.of(context).scaffoldBackgroundColor,
        margin: const EdgeInsets.only(bottom: 16),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Padding(
              padding: const EdgeInsets.all(12),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  CachedAvatar(
                    avatarHash: widget.avatarHash,
                    avatarColor: widget.avatarColor,
                    imageUrlFromHash: widget.imageUrlFromHash,
                    imageCacheService: _imageCacheService,
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    widget.name,
                                    style: const TextStyle(
                                      color: Colors.white,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                  Text(
                                    widget.cursoInfo,
                                    style: const TextStyle(
                                      color: Colors.white60,
                                      fontSize: 12,
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            const Icon(Icons.more_horiz, color: Colors.white70),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Text(
                          widget.content,
                          textAlign: TextAlign.justify,
                          style: const TextStyle(
                            color: Colors.white,
                            height: 1.4,
                          ),
                        ),
                        if (widget.imageHashes.isNotEmpty) ...[
                          const SizedBox(height: 12),
                          if (imageCount == 1)
                            CachedPostImage(
                              url: widget.imageUrlFromHash(images[0]),
                              height: 180,
                              borderRadius: BorderRadius.circular(8),
                              onTap: _openPostImages,
                              imageCacheService: _imageCacheService,
                            )
                          else
                            CachedPostImagesGrid(
                              urls: images
                                  .map((hash) => widget.imageUrlFromHash(hash))
                                  .toList(),
                              onImageTap: _openPostImages,
                              imageCacheService: _imageCacheService,
                              height: 180,
                              borderRadius: BorderRadius.circular(8),
                            ),
                        ],
                        const SizedBox(height: 12),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                const Icon(
                                  Icons.mode_comment_outlined,
                                  color: Colors.white54,
                                  size: 20,
                                ),
                                const SizedBox(width: 4),
                                Text(
                                  '${widget.comments}',
                                  style: const TextStyle(
                                    color: Colors.white54,
                                    fontSize: 12,
                                  ),
                                ),
                              ],
                            ),
                            _LikeButton(
                              liked: widget.liked,
                              idPublicacao: widget.idPublicacao,
                              tipoAutorPublicacao: widget.tipoAutorPublicacao,
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 12),
            const Divider(color: Colors.white12, height: 1),
          ],
        ),
      ),
    );
  }
}

class _LikeButton extends StatefulWidget {
  final bool liked;
  final int idPublicacao;
  final TipoAutorPublicacao tipoAutorPublicacao;

  const _LikeButton({
    required this.liked,
    required this.idPublicacao,
    required this.tipoAutorPublicacao,
  });

  @override
  State<_LikeButton> createState() => __LikeButtonState();
}

class __LikeButtonState extends State<_LikeButton>
    with SingleTickerProviderStateMixin {
  late bool _liked;
  late AnimationController _controller;
  late Animation<double> _scaleAnimation;
  late Animation<double> _opacityAnimation;

  final PublicacaoRepository _repo = PublicacaoRepository(PublicacaoService());

  @override
  void initState() {
    super.initState();
    _liked = widget.liked;

    _controller = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 300),
    );

    _scaleAnimation = TweenSequence([
      TweenSequenceItem(tween: Tween(begin: 1.0, end: 1.4), weight: 50),
      TweenSequenceItem(tween: Tween(begin: 1.4, end: 1.0), weight: 50),
    ]).animate(CurvedAnimation(parent: _controller, curve: Curves.easeOut));

    _opacityAnimation = TweenSequence([
      TweenSequenceItem(tween: Tween(begin: 1.0, end: 0.6), weight: 50),
      TweenSequenceItem(tween: Tween(begin: 0.6, end: 1.0), weight: 50),
    ]).animate(CurvedAnimation(parent: _controller, curve: Curves.easeOut));
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  void _toggle() async {
    final chaveAutor = widget.tipoAutorPublicacao.atributo;

    setState(() => _liked = !_liked);
    _controller.forward(from: 0);

    try {
      if (_liked) {
        await _repo.adicionarReacao(
          chaveAutor: chaveAutor,
          idPublicacao: widget.idPublicacao,
        );
      } else {
        await _repo.removerReacao(
          chaveAutor: chaveAutor,
          idPublicacao: widget.idPublicacao,
        );
      }
    } catch (e) {
      setState(() => _liked = !_liked);
    }
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: _toggle,
      behavior: HitTestBehavior.translucent,
      child: Padding(
        padding: const EdgeInsets.all(4.0),
        child: AnimatedBuilder(
          animation: _controller,
          builder: (_, child) {
            return Transform.scale(
              scale: _scaleAnimation.value,
              child: Opacity(
                opacity: _opacityAnimation.value,
                child: Icon(
                  _liked ? Icons.favorite : Icons.favorite_border,
                  color: _liked ? Colors.red : Colors.white54,
                  size: 20,
                ),
              ),
            );
          },
        ),
      ),
    );
  }
}
