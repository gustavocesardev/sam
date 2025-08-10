import 'package:flutter/material.dart';
import 'dart:typed_data';

import 'package:sam_app/data/cache/image_cache_service.dart';
import 'package:sam_app/presentation/pages/publicacoes/post_images_page.dart';
import 'package:sam_app/presentation/widgets/cached/cached_avatar.dart';
import 'package:sam_app/presentation/widgets/cached/cached_post_image.dart';
import 'package:sam_app/presentation/widgets/cached/cached_post_image_grid.dart';
import 'package:sam_app/shared/constants.dart';

class FeedPostCard extends StatefulWidget {
  final String name;
  final String cursoInfo;
  final String content;
  final int comments;
  final int likes;
  final List<String> imageHashes;
  final String? avatarHash;
  final Color avatarColor;

  const FeedPostCard({
    super.key,
    required this.name,
    required this.cursoInfo,
    required this.content,
    required this.comments,
    required this.likes,
    required this.imageHashes,
    required this.avatarColor,
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
              ? _imageCacheService.getCachedImageBytes(widget.imageUrlFromHash(widget.avatarHash!))
              : null,
          imagesBytes: widget.imageHashes
              .map((hash) => _imageCacheService.getCachedImageBytes(widget.imageUrlFromHash(hash)))
              .whereType<Uint8List>()
              .toList(),
          comments: widget.comments,
          likes: widget.likes,
        ),
      )
    );
  }

  @override
  Widget build(BuildContext context) {
    final imageCount = widget.imageHashes.length.clamp(1, 4);
    final images = widget.imageHashes.take(imageCount).toList();

    return Card(
      color: Theme.of(context).scaffoldBackgroundColor,
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          /// Conteúdo do Card
          Padding(
            padding: const EdgeInsets.all(12),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [

                /// Avatar
                CachedAvatar(
                  avatarHash: widget.avatarHash,
                  avatarColor: widget.avatarColor,
                  imageUrlFromHash: widget.imageUrlFromHash,
                  imageCacheService: _imageCacheService
                ),
                const SizedBox(width: 12),

                /// Coluna da direita: Nome, curso, conteúdo, imagens, etc.
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [

                      /// Nome + curso + opções
                      Row(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  widget.name,
                                  style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600),
                                ),
                                Text(
                                  widget.cursoInfo,
                                  style: const TextStyle(color: Colors.white60, fontSize: 12),
                                ),
                              ],
                            ),
                          ),
                          const Icon(Icons.more_horiz, color: Colors.white70),
                        ],
                      ),

                      const SizedBox(height: 8),

                      /// Conteúdo do post
                      Text(
                        widget.content,
                        textAlign: TextAlign.justify,
                        style: const TextStyle(color: Colors.white, height: 1.4),
                      ),

                      /// Imagens do post
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
                            urls: images.map((hash) => widget.imageUrlFromHash(hash)).toList(),
                            onImageTap: _openPostImages,
                            imageCacheService: _imageCacheService,
                            height: 180,
                            borderRadius: BorderRadius.circular(8),
                          ),
                      ],

                      const SizedBox(height: 12),

                      /// Comentários e curtidas
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Row(
                            children: [
                              const Icon(Icons.mode_comment_outlined, color: Colors.white54, size: 20),
                              const SizedBox(width: 4),
                              Text('${widget.comments}', style: const TextStyle(color: Colors.white54, fontSize: 12)),
                            ],
                          ),
                          const Icon(Icons.favorite_border, color: Colors.white54, size: 20),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          const Divider(color: Colors.white12, height: 1),
        ],
      ),
    );
  }
}
