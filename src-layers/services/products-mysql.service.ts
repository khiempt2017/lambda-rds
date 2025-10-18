/**
 * products-mysql.service.ts
 * BP-api-serverless
 * Service layer for Products MySQL operations
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { Products, ProductsMySQLModel, ProductsData } from '../database/models/products-mysql.model';
import { BaseMySQLService } from './base-mysql.service';
import { ResultSetHeader } from 'mysql2/promise';

export class ProductsMySQLService extends BaseMySQLService<Products> {
  private productsModel: ProductsMySQLModel;

  constructor(productsModel: ProductsMySQLModel = new ProductsMySQLModel()) {
    super(productsModel);
    this.productsModel = productsModel;
  }

  /**
   * Find active products
   * @returns Array of active products
   */
  public async findActiveProducts(): Promise<Products[]> {
    return await this.findBy({ status: 1 });
  }

  /**
   * Find products by price range
   * @param minPrice - Minimum price
   * @param maxPrice - Maximum price
   * @returns Array of products in the price range
   */
  public async findProductsByPriceRange(minPrice: number, maxPrice: number): Promise<Products[]> {
    return await this.productsModel.findByPriceRange(minPrice, maxPrice);
  }

  /**
   * Create a new product
   * @param data - Product data
   * @returns Insert result
   */
  public async createProduct(data: Omit<ProductsData, 'id'>): Promise<ResultSetHeader> {
    return await this.productsModel.createProduct(data);
  }

  /**
   * Update product by ID
   * @param id - Product ID
   * @param data - Data to update
   * @returns Update result
   */
  public async updateProduct(id: number, data: Partial<Omit<ProductsData, 'id'>>): Promise<ResultSetHeader> {
    return await this.productsModel.updateProduct(id, data);
  }

  /**
   * Delete product by ID
   * @param id - Product ID
   * @returns Delete result
   */
  public async deleteProduct(id: number): Promise<ResultSetHeader> {
    return await this.productsModel.deleteProduct(id);
  }

  /**
   * Get product statistics
   * @returns Statistics object
   */
  public async getProductStatistics(): Promise<any> {
    return await this.productsModel.getStatistics();
  }

  /**
   * Get brand statistics
   * @returns Array of brand statistics
   */
  public async getBrandStatistics(): Promise<any[]> {
    return await this.productsModel.getBrandStatistics();
  }

  /**
   * Count products by category
   * @param categoryId - Category ID
   * @returns Number of products in the category
   */
  public async countProductsByCategory(categoryId: number): Promise<number> {
    return await this.productsModel.countByCategoryId(categoryId);
  }

  /**
   * Count products by brand
   * @param brand - Brand name
   * @returns Number of products with the brand
   */
  public async countProductsByBrand(brand: string): Promise<number> {
    return await this.productsModel.countByBrand(brand);
  }
}
