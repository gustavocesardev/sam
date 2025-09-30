import 'package:flutter/material.dart';
import 'package:sam_app/data/cache/image_cache_service.dart';
import 'package:sam_app/presentation/widgets/cached/cached_post_image.dart';

class CachedPostImagesGrid extends StatelessWidget {
  final List<String> urls;
  final double height;
  final BorderRadius borderRadius;
  final VoidCallback onImageTap;
  final ImageCacheService imageCacheService;

  const CachedPostImagesGrid({
    super.key,
    required this.urls,
    required this.height,
    required this.borderRadius,
    required this.onImageTap,
    required this.imageCacheService,
  });

  @override
  Widget build(BuildContext context) {
    final imageCount = urls.length.clamp(1, 4);
    final images = urls.take(imageCount).toList();

    if (imageCount == 1) {
      return CachedPostImage(
        url: images[0],
        height: height,
        borderRadius: borderRadius,
        onTap: onImageTap,
        imageCacheService: imageCacheService,
      );
    }

    if (imageCount == 2) {
      return SizedBox(
        height: height,
        child: Row(
          children: images
            .map(
              (url) => Expanded(
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 2),
                  child: CachedPostImage(
                    url: url,
                    height: height,
                    borderRadius: borderRadius,
                    onTap: onImageTap,
                    imageCacheService: imageCacheService,
                  ),
                ),
              ),
            )
            .toList(),
        ),
      );
    }

    if (imageCount == 3) {
      return SizedBox(
        height: height,
        child: Row(
          children: [
            Expanded(
              flex: 2,
              child: CachedPostImage(
                url: images[0],
                height: height,
                borderRadius: borderRadius,
                onTap: onImageTap,
                imageCacheService: imageCacheService,
              ),
            ),
            const SizedBox(width: 4),
            Expanded(
              flex: 1,
              child: Column(
                children: [
                  Expanded(
                    child: CachedPostImage(
                      url: images[1],
                      height: height / 2 - 2,
                      borderRadius: borderRadius,
                      onTap: onImageTap,
                      imageCacheService: imageCacheService,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Expanded(
                    child: CachedPostImage(
                      url: images[2],
                      height: height / 2 - 2,
                      borderRadius: borderRadius,
                      onTap: onImageTap,
                      imageCacheService: imageCacheService,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      );
    }

    /// 4 ou mais imagens (exibe 4)
    return Column(
      children: [
        SizedBox(
          height: height,
          child: Row(
            children: images
                .take(2)
                .map(
                  (url) => Expanded(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 2),
                      child: CachedPostImage(
                        url: url,
                        height: height,
                        borderRadius: borderRadius,
                        onTap: onImageTap,
                        imageCacheService: imageCacheService,
                      ),
                    ),
                  ),
                )
                .toList(),
          ),
        ),
        const SizedBox(height: 4),
        SizedBox(
          height: height,
          child: Row(
            children: images
                .skip(2)
                .take(2)
                .map(
                  (url) => Expanded(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 2),
                      child: CachedPostImage(
                        url: url,
                        height: height,
                        borderRadius: borderRadius,
                        onTap: onImageTap,
                        imageCacheService: imageCacheService,
                      ),
                    ),
                  ),
                )
                .toList(),
          ),
        ),
      ],
    );
  }
}
